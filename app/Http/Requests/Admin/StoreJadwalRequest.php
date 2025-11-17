<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreJadwalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'guru_id' => [
                'required',
                'exists:guru,id',
            ],
            'mata_pelajaran_id' => [
                'required',
                'exists:mata_pelajaran,id',
            ],
            'kelas_id' => [
                'required',
                'exists:kelas,id',
            ],
            'hari' => [
                'required',
                Rule::in(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i',
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai',
            ],
            'ruangan' => [
                'nullable',
                'string',
                'max:50',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'guru_id' => 'Guru',
            'mata_pelajaran_id' => 'Mata Pelajaran',
            'kelas_id' => 'Kelas',
            'hari' => 'Hari',
            'jam_mulai' => 'Jam Mulai',
            'jam_selesai' => 'Jam Selesai',
            'ruangan' => 'Ruangan',
            'is_active' => 'Status Aktif',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'guru_id.required' => ':attribute wajib dipilih',
            'guru_id.exists' => ':attribute tidak ditemukan',
            'mata_pelajaran_id.required' => ':attribute wajib dipilih',
            'mata_pelajaran_id.exists' => ':attribute tidak ditemukan',
            'kelas_id.required' => ':attribute wajib dipilih',
            'kelas_id.exists' => ':attribute tidak ditemukan',
            'hari.required' => ':attribute wajib dipilih',
            'hari.in' => ':attribute tidak valid',
            'jam_mulai.required' => ':attribute wajib diisi',
            'jam_mulai.date_format' => ':attribute format tidak valid (HH:MM)',
            'jam_selesai.required' => ':attribute wajib diisi',
            'jam_selesai.date_format' => ':attribute format tidak valid (HH:MM)',
            'jam_selesai.after' => ':attribute harus setelah jam mulai',
            'ruangan.max' => ':attribute maksimal :max karakter',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->checkScheduleConflict()) {
                $validator->errors()->add(
                    'jadwal',
                    'Jadwal bentrok dengan jadwal lain'
                );
            }
        });
    }

    /**
     * Check if schedule conflicts with existing schedules
     */
    protected function checkScheduleConflict(): bool
    {
        $query = \App\Models\JadwalMengajar::where('guru_id', $this->guru_id)
            ->where('hari', $this->hari)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function ($q2) {
                        $q2->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            });

        // Exclude current record if updating
        if ($jadwalId = $this->route('jadwal') ?? $this->route('id')) {
            $query->where('id', '!=', $jadwalId);
        }

        return $query->exists();
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors(),
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        if ($this->expectsJson()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses',
                ], 403)
            );
        }

        parent::failedAuthorization();
    }
}
