<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AbsensiSelfieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'guru';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'foto' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png',
                'max:2048', // 2MB
            ],
            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],
            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
            'keterangan' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'foto' => 'Foto Selfie',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'keterangan' => 'Keterangan',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'foto.required' => ':attribute wajib diupload',
            'foto.image' => ':attribute harus berupa gambar',
            'foto.mimes' => ':attribute harus format JPEG, JPG, atau PNG',
            'foto.max' => ':attribute maksimal 2MB',
            'latitude.required' => ':attribute wajib diisi',
            'latitude.between' => ':attribute harus antara -90 dan 90',
            'longitude.required' => ':attribute wajib diisi',
            'longitude.between' => ':attribute harus antara -180 dan 180',
            'keterangan.max' => ':attribute maksimal :max karakter',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422)
        );
    }

    /**
     * Handle a failed authorization attempt.
     */
    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses',
            ], 403)
        );
    }
}
