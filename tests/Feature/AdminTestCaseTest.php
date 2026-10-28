<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\Submission;
use App\Models\SubmissionResult;
use App\Models\TestCase as ExerciseTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AdminTestCaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        config(['services.judge0.url' => 'http://judge0.test']);
    }

    // ---------------------------------------------------------------
    // Akses
    // ---------------------------------------------------------------

    public function test_guest_and_learner_cannot_manage_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $testCase = $this->makeTestCase($exercise);

        $this->get(route('admin.latihan.test-cases', $exercise))->assertRedirect(route('login'));
        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload())->assertRedirect(route('login'));
        $this->delete(route('admin.test-cases.destroy', $testCase))->assertRedirect(route('login'));
        $this->get(route('admin.latihan.answer-key', $exercise))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['role' => 'learner']));

        $this->get(route('admin.latihan.test-cases', $exercise))->assertForbidden();
        $this->get(route('admin.latihan.test-cases.create', $exercise))->assertForbidden();
        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload())->assertForbidden();
        $this->get(route('admin.test-cases.edit', $testCase))->assertForbidden();
        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload())->assertForbidden();
        $this->put(route('admin.test-cases.active.update', $testCase), ['is_active' => 0])->assertForbidden();
        $this->delete(route('admin.test-cases.destroy', $testCase))->assertForbidden();
        $this->get(route('admin.latihan.answer-key', $exercise))->assertForbidden();
        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'print(1)'])->assertForbidden();

        $this->assertDatabaseCount('test_cases', 1);
    }

    // ---------------------------------------------------------------
    // Halaman daftar test case
    // ---------------------------------------------------------------

    public function test_draft_without_test_cases_is_told_it_cannot_be_published(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('Soal belum bisa diterbitkan sampai punya minimal satu test case aktif yang terlihat')
            ->assertDontSee('Terbitkan Soal')
            ->assertSee(route('admin.latihan.test-cases.create', $exercise));
    }

    public function test_draft_with_only_hidden_test_cases_still_cannot_be_published(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise, ['expected_output' => 'KELUARAN-RAHASIA', 'is_hidden' => true]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('KELUARAN-RAHASIA')
            ->assertSee('Tersembunyi')
            ->assertSee('Soal belum bisa diterbitkan sampai punya minimal satu test case aktif yang terlihat')
            ->assertDontSee('Terbitkan Soal');
    }

    public function test_draft_with_a_visible_test_case_shows_publish_button(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('Soal sudah siap diterbitkan')
            ->assertSee('Simpan sebagai Draft')
            ->assertSee('Terbitkan Soal')
            ->assertSee(route('admin.latihan.status.update', $exercise));
    }

    public function test_published_exercise_without_visible_test_case_gets_a_warning(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $this->makeTestCase($exercise, ['is_hidden' => true]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('sedang terbit, tetapi tidak punya test case aktif yang terlihat')
            ->assertSee('Tarik ke Draft');
    }

    public function test_published_exercise_with_visible_test_case_can_be_pulled_back_to_draft(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $this->makeTestCase($exercise);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('Soal ini sudah terbit dan tampil untuk siswa')
            ->assertSee('Tarik ke Draft')
            ->assertDontSee('Terbitkan Soal');
    }

    public function test_list_summarizes_active_visible_hidden_and_inactive_counts(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        $this->makeTestCase($exercise, ['is_hidden' => true]);
        $this->makeTestCase($exercise, ['is_active' => false]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('2 aktif (1 tampil sebagai contoh, 1 tersembunyi)')
            ->assertSee('1 nonaktif')
            ->assertSee('Nonaktif');
    }

    public function test_exercise_list_links_to_test_cases_with_active_count(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        $this->makeTestCase($exercise);
        $this->makeTestCase($exercise, ['is_active' => false]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan'))
            ->assertOk()
            ->assertSee(route('admin.latihan.test-cases', $exercise))
            ->assertSee('Test Case (2)');
    }

    // ---------------------------------------------------------------
    // Tambah
    // ---------------------------------------------------------------

    public function test_admin_sees_create_form_with_type_choice(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases.create', $exercise))
            ->assertOk()
            ->assertSee('Tambah Test Case')
            ->assertSee('Jenis test case')
            ->assertSee('Input / Output')
            ->assertSee('Assertion')
            ->assertSee('Simpan Test Case');
    }

    public function test_store_saves_input_output_test_case_with_defaults(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload())
            ->assertRedirect(route('admin.latihan.test-cases', $exercise))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('test_cases', [
            'exercise_id' => $exercise->id,
            'input' => "2\n3",
            'expected_output' => '5',
            'assertion_code' => null,
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_store_normalizes_line_endings_and_can_hide_test_case(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'input' => "2\r\n3",
            'is_hidden' => 1,
        ]));

        $testCase = ExerciseTestCase::firstOrFail();

        $this->assertSame("2\n3", $testCase->input);
        $this->assertTrue($testCase->is_hidden);
    }

    public function test_store_assigns_next_sort_order_per_exercise(): void
    {
        $exercise = $this->makeExercise();
        $other = $this->makeExercise();
        $this->makeTestCase($other, ['sort_order' => 9]);
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload());
        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload());

        $this->assertSame([1, 2], ExerciseTestCase::where('exercise_id', $exercise->id)->orderBy('sort_order')->pluck('sort_order')->all());
    }

    public function test_type_is_required_and_must_be_valid(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload(['jenis' => '']))
            ->assertSessionHasErrors('jenis');

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload(['jenis' => 'campuran']))
            ->assertSessionHasErrors('jenis');

        $this->assertDatabaseCount('test_cases', 0);
    }

    public function test_each_type_requires_its_own_field(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'jenis' => 'io',
            'expected_output' => '',
            'assertion_code' => 'assert 1 == 1',
        ]))->assertSessionHasErrors('expected_output');

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'jenis' => 'assertion',
            'assertion_code' => '',
            'expected_output' => '5',
        ]))->assertSessionHasErrors('assertion_code');

        $this->assertDatabaseCount('test_cases', 0);
    }

    public function test_assertion_type_stores_only_assertion_code(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'jenis' => 'assertion',
            'assertion_code' => 'assert jumlah(2, 3) == 5',
            'input' => '2',
            'expected_output' => '5',
        ]))->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('test_cases', [
            'exercise_id' => $exercise->id,
            'assertion_code' => 'assert jumlah(2, 3) == 5',
            'input' => null,
            'expected_output' => '',
        ]);
    }

    public function test_input_output_type_discards_assertion_code(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload([
            'jenis' => 'io',
            'assertion_code' => 'assert 1 == 1',
        ]))->assertSessionDoesntHaveErrors();

        $this->assertDatabaseHas('test_cases', [
            'exercise_id' => $exercise->id,
            'assertion_code' => null,
            'expected_output' => '5',
        ]);
    }

    public function test_weight_must_be_within_range(): void
    {
        $exercise = $this->makeExercise();
        $this->actingAs($this->makeAdmin());

        foreach ([0, 101, 'abc'] as $invalid) {
            $this->post(route('admin.latihan.test-cases.store', $exercise), $this->validPayload(['weight' => $invalid]))
                ->assertSessionHasErrors('weight');
        }

        $this->assertDatabaseCount('test_cases', 0);
    }

    // ---------------------------------------------------------------
    // Edit
    // ---------------------------------------------------------------

    public function test_admin_sees_edit_form_with_existing_values(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise(), ['expected_output' => 'NILAI-LAMA']);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.test-cases.edit', $testCase))
            ->assertOk()
            ->assertSee('Edit Test Case')
            ->assertSee('NILAI-LAMA');
    }

    public function test_edit_form_shows_assertion_code_for_assertion_test_case(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise(), [
            'input' => null,
            'expected_output' => '',
            'assertion_code' => 'assert jumlah(2, 3) == 5',
        ]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.test-cases.edit', $testCase))
            ->assertOk()
            ->assertSee('assert jumlah(2, 3) == 5');
    }

    public function test_update_changes_test_case(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise());
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload([
            'input' => '10',
            'expected_output' => '20',
            'weight' => 5,
            'is_hidden' => 1,
            'sort_order' => 4,
        ]))->assertRedirect(route('admin.latihan.test-cases', $testCase->exercise_id));

        $this->assertDatabaseHas('test_cases', [
            'id' => $testCase->id,
            'input' => '10',
            'expected_output' => '20',
            'weight' => 5,
            'is_hidden' => true,
            'sort_order' => 4,
        ]);
    }

    public function test_update_can_switch_type_and_clears_the_other_type(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise());
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload([
            'jenis' => 'assertion',
            'assertion_code' => 'assert jumlah(1, 1) == 2',
        ]));

        $this->assertDatabaseHas('test_cases', [
            'id' => $testCase->id,
            'assertion_code' => 'assert jumlah(1, 1) == 2',
            'input' => null,
            'expected_output' => '',
        ]);
    }

    public function test_update_validates_input(): void
    {
        $testCase = $this->makeTestCase($this->makeExercise());
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.update', $testCase), $this->validPayload(['expected_output' => '', 'weight' => 0]))
            ->assertSessionHasErrors(['expected_output', 'weight']);
    }

    // ---------------------------------------------------------------
    // Hapus dan nonaktifkan
    // ---------------------------------------------------------------

    public function test_destroy_deletes_only_that_test_case(): void
    {
        $exercise = $this->makeExercise();
        $first = $this->makeTestCase($exercise);
        $second = $this->makeTestCase($exercise);
        $this->actingAs($this->makeAdmin());

        $this->delete(route('admin.test-cases.destroy', $first))
            ->assertRedirect(route('admin.latihan.test-cases', $exercise))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('test_cases', ['id' => $first->id]);
        $this->assertDatabaseHas('test_cases', ['id' => $second->id]);
    }

    public function test_test_case_with_student_results_cannot_be_deleted(): void
    {
        $exercise = $this->makeExercise();
        $testCase = $this->makeTestCase($exercise);
        $this->attachStudentResult($testCase);
        $this->actingAs($this->makeAdmin());

        $this->delete(route('admin.test-cases.destroy', $testCase))
            ->assertSessionHasErrors('test_case');

        $this->assertDatabaseHas('test_cases', ['id' => $testCase->id]);
        $this->assertDatabaseCount('submission_results', 1);
    }

    public function test_test_case_with_student_progress_cannot_be_deleted(): void
    {
        $exercise = $this->makeExercise();
        $testCase = $this->makeTestCase($exercise);
        DB::table('test_case_progress')->insert([
            'user_id' => User::factory()->create(['role' => 'learner'])->id,
            'exercise_id' => $exercise->id,
            'test_case_id' => $testCase->id,
            'code_hash' => str_repeat('a', 64),
            'status' => 'passed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->actingAs($this->makeAdmin());

        $this->delete(route('admin.test-cases.destroy', $testCase))
            ->assertSessionHasErrors('test_case');

        $this->assertDatabaseHas('test_cases', ['id' => $testCase->id]);
    }

    public function test_list_replaces_delete_with_history_note_for_test_case_with_results(): void
    {
        $exercise = $this->makeExercise();
        $withResults = $this->makeTestCase($exercise, ['sort_order' => 1]);
        $this->makeTestCase($exercise, ['sort_order' => 2]);
        $this->attachStudentResult($withResults);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.test-cases', $exercise))
            ->assertOk()
            ->assertSee('Ada riwayat')
            ->assertSee('Nonaktifkan')
            ->assertSee('aria-label="Hapus test case 2"', false)
            ->assertDontSee('aria-label="Hapus test case 1"', false);
    }

    public function test_test_case_can_be_deactivated_and_reactivated(): void
    {
        $exercise = $this->makeExercise();
        $testCase = $this->makeTestCase($exercise);
        $this->attachStudentResult($testCase);
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.active.update', $testCase), ['is_active' => 0])
            ->assertSessionHas('status');
        $this->assertFalse($testCase->fresh()->is_active);
        $this->assertDatabaseCount('submission_results', 1);

        $this->put(route('admin.test-cases.active.update', $testCase), ['is_active' => 1])
            ->assertSessionHas('status');
        $this->assertTrue($testCase->fresh()->is_active);

        $this->put(route('admin.test-cases.active.update', $testCase), ['is_active' => 'bukan-boolean'])
            ->assertSessionHasErrors('is_active');
    }

    public function test_last_visible_test_case_of_published_exercise_is_protected(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $only = $this->makeTestCase($exercise);
        $hidden = $this->makeTestCase($exercise, ['is_hidden' => true, 'sort_order' => 2]);
        $this->actingAs($this->makeAdmin());

        $this->delete(route('admin.test-cases.destroy', $only))->assertSessionHasErrors('test_case');
        $this->put(route('admin.test-cases.active.update', $only), ['is_active' => 0])->assertSessionHasErrors('is_active');
        $this->put(route('admin.test-cases.update', $only), $this->validPayload(['is_hidden' => 1]))->assertSessionHasErrors('is_hidden');

        $this->assertDatabaseHas('test_cases', ['id' => $only->id, 'is_active' => true, 'is_hidden' => false]);

        // Yang tersembunyi tidak menjaga syarat terbit, jadi boleh dihapus.
        $this->delete(route('admin.test-cases.destroy', $hidden))->assertSessionDoesntHaveErrors();
        $this->assertDatabaseMissing('test_cases', ['id' => $hidden->id]);
    }

    public function test_visible_test_case_can_be_removed_while_another_visible_one_remains(): void
    {
        $exercise = $this->makeExercise(['is_published' => true]);
        $first = $this->makeTestCase($exercise);
        $second = $this->makeTestCase($exercise, ['sort_order' => 2]);
        $this->actingAs($this->makeAdmin());

        $this->delete(route('admin.test-cases.destroy', $first))->assertSessionDoesntHaveErrors();
        $this->assertDatabaseMissing('test_cases', ['id' => $first->id]);

        // Sekarang $second tinggal satu-satunya.
        $this->put(route('admin.test-cases.active.update', $second), ['is_active' => 0])
            ->assertSessionHasErrors('is_active');
    }

    public function test_draft_exercise_has_no_protection_on_its_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $only = $this->makeTestCase($exercise);
        $this->actingAs($this->makeAdmin());

        $this->put(route('admin.test-cases.active.update', $only), ['is_active' => 0])
            ->assertSessionDoesntHaveErrors();

        $this->assertFalse($only->fresh()->is_active);
    }

    // ---------------------------------------------------------------
    // Sisi siswa
    // ---------------------------------------------------------------

    public function test_learner_sees_visible_test_cases_but_not_hidden_or_inactive_ones(): void
    {
        $exercise = $this->makeExercise(['is_published' => true, 'slug' => 'soal-uji']);
        $this->makeTestCase($exercise, ['input' => '1', 'expected_output' => 'KELUARAN-CONTOH']);
        $this->makeTestCase($exercise, ['input' => '2', 'expected_output' => 'KELUARAN-TERSEMBUNYI', 'is_hidden' => true]);
        $this->makeTestCase($exercise, ['input' => '3', 'expected_output' => 'KELUARAN-NONAKTIF', 'is_active' => false]);

        $this->actingAs(User::factory()->create(['role' => 'learner']))
            ->get(route('latihan.show', 'soal-uji'))
            ->assertOk()
            ->assertSee('KELUARAN-CONTOH')
            ->assertDontSee('KELUARAN-TERSEMBUNYI')
            ->assertDontSee('KELUARAN-NONAKTIF');
    }

    // ---------------------------------------------------------------
    // Uji Kunci Jawaban
    // ---------------------------------------------------------------

    public function test_answer_key_page_is_prefilled_with_starter_code(): void
    {
        $exercise = $this->makeExercise(['starter_code' => "a = int(input())\n"]);
        $this->actingAs($this->makeAdmin());

        $this->get(route('admin.latihan.answer-key', $exercise))
            ->assertOk()
            ->assertSee('Uji Kunci Jawaban')
            ->assertSee('a = int(input())')
            ->assertSee('Jalankan ke Semua Test Case');
    }

    public function test_answer_key_runs_solution_against_all_active_test_cases_including_hidden(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        $this->makeTestCase($exercise, ['expected_output' => 'KELUARAN-RAHASIA', 'is_hidden' => true, 'sort_order' => 2]);
        $this->makeTestCase($exercise, ['is_active' => false, 'sort_order' => 3]);
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => "1\n",
            'time' => '0.01',
        ], 200));
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'print(1)'])
            ->assertOk()
            ->assertSee('2 dari 2 test case lolos')
            ->assertSee('KELUARAN-RAHASIA')
            ->assertSee('Tersembunyi');

        Http::assertSentCount(2);
    }

    public function test_answer_key_reports_failing_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise, ['expected_output' => '8']);
        Http::fake(fn () => Http::response([
            'status' => ['id' => 4, 'description' => 'Wrong Answer'],
            'stdout' => "7\n",
            'time' => '0.01',
        ], 200));
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'print(7)'])
            ->assertOk()
            ->assertSee('0 dari 1 test case lolos')
            ->assertSee('Gagal')
            ->assertSee('Wrong Answer');
    }

    public function test_answer_key_appends_assertion_code_to_the_solution(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise, [
            'input' => null,
            'expected_output' => '',
            'assertion_code' => 'assert jumlah(2, 3) == 5',
        ]);
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => '',
            'time' => '0.01',
        ], 200));
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'def jumlah(a, b): return a + b'])
            ->assertOk()
            ->assertSee('1 dari 1 test case lolos');

        Http::assertSent(fn ($request) => str_contains($request['source_code'], 'def jumlah(a, b): return a + b')
            && str_contains($request['source_code'], 'assert jumlah(2, 3) == 5'));
    }

    public function test_answer_key_requires_source_code(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        Http::fake();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => ''])
            ->assertSessionHasErrors('source_code');

        Http::assertNothingSent();
    }

    public function test_answer_key_explains_when_there_are_no_active_test_cases(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise, ['is_active' => false]);
        Http::fake();
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'print(1)'])
            ->assertOk()
            ->assertSee('belum punya test case aktif');

        Http::assertNothingSent();
    }

    public function test_answer_key_shows_error_when_judge0_is_unreachable(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        Http::fake(fn () => Http::response('layanan mati', 500));
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'print(1)'])
            ->assertOk()
            ->assertSee('Tidak dapat menjalankan kode lewat Judge0');
    }

    public function test_answer_key_does_not_create_submissions(): void
    {
        $exercise = $this->makeExercise();
        $this->makeTestCase($exercise);
        Http::fake(fn () => Http::response([
            'status' => ['id' => 3, 'description' => 'Accepted'],
            'stdout' => "1\n",
            'time' => '0.01',
        ], 200));
        $this->actingAs($this->makeAdmin());

        $this->post(route('admin.latihan.answer-key.run', $exercise), ['source_code' => 'print(1)']);

        $this->assertDatabaseCount('submissions', 0);
    }

    // ---------------------------------------------------------------
    // Helper
    // ---------------------------------------------------------------

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'jenis' => 'io',
            'input' => "2\n3",
            'expected_output' => '5',
            'assertion_code' => '',
            'weight' => 1,
            'sort_order' => '',
        ], $overrides);
    }

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function attachStudentResult(ExerciseTestCase $testCase): void
    {
        $submission = Submission::create([
            'user_id' => User::factory()->create(['role' => 'learner'])->id,
            'exercise_id' => $testCase->exercise_id,
            'source_code' => 'print(1)',
            'status' => 'accepted',
            'score' => 100,
            'passed_tests' => 1,
            'total_tests' => 1,
            'execution_time_ms' => 10,
            'submitted_at' => now(),
        ]);

        SubmissionResult::create([
            'submission_id' => $submission->id,
            'test_case_id' => $testCase->id,
            'status' => 'passed',
            'actual_output' => '1',
            'execution_time_ms' => 10,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeTestCase(Exercise $exercise, array $overrides = []): ExerciseTestCase
    {
        return ExerciseTestCase::create(array_merge([
            'exercise_id' => $exercise->id,
            'input' => '1',
            'expected_output' => '1',
            'weight' => 1,
            'is_hidden' => false,
            'is_active' => true,
            'sort_order' => 1,
        ], $overrides));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function makeExercise(array $overrides = []): Exercise
    {
        $now = now();
        $uid = uniqid();

        $courseId = DB::table('courses')->insertGetId([
            'title' => 'Python', 'slug' => 'python-'.$uid, 'description' => 'Test course',
            'level' => 'beginner', 'is_published' => true, 'sort_order' => 1,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $moduleId = DB::table('modules')->insertGetId([
            'course_id' => $courseId, 'title' => 'Dasar '.$uid, 'slug' => 'dasar-'.$uid,
            'description' => 'Test module', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);
        $lessonId = DB::table('lessons')->insertGetId([
            'module_id' => $moduleId, 'title' => 'Fungsi', 'slug' => 'pelajaran-'.$uid,
            'content' => 'Test lesson', 'sort_order' => 1, 'is_published' => true,
            'created_at' => $now, 'updated_at' => $now,
        ]);

        return Exercise::create(array_merge([
            'lesson_id' => $lessonId,
            'title' => 'Jumlah',
            'slug' => 'jumlah-'.$uid,
            'description' => 'Jumlahkan dua bilangan.',
            'difficulty' => 'easy',
            'time_limit_ms' => 2000,
            'memory_limit_mb' => 128,
            'sort_order' => 1,
            'is_published' => false,
        ], $overrides));
    }
}