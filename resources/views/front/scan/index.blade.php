@extends('front.layout.main')

@section('title', 'Analisis URL - URLinsight')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2 mb-3">
                        URL Security Analyzer
                    </span>

                    <h1 class="fw-bold display-5 mb-3">
                        Kenali Risiko Sebelum
                        <span class="text-primary">Membuka Link</span>
                    </h1>

                    <p class="text-secondary fs-5">
                        Periksa URL yang mencurigakan menggunakan teknologi
                        Machine Learning untuk membantu mengenali potensi
                        ancaman phishing.
                    </p>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h5 class="fw-bold mb-2">Analisis URL</h5>

                            <p class="text-secondary mb-4">
                                Masukkan alamat website yang ingin kamu periksa.
                            </p>

                            <form action="{{ route('scan.store') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="url" class="form-label fw-semibold">
                                        Alamat URL
                                    </label>

                                    <input
                                        type="text"
                                        name="url"
                                        id="url"
                                        class="form-control form-control-lg @error('url') is-invalid @enderror"
                                        placeholder="https://example.com/login"
                                        value="{{ old('url') }}"
                                        maxlength="2048"
                                        required
                                    >

                                    @error('url')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-shield-check me-2"></i>
                                    Analisis URL
                                </button>
                            </form>

                            <div class="d-flex align-items-start gap-2 mt-4">
                                <span class="text-primary">ⓘ</span>
                                <small class="text-secondary">
                                    Kamu dapat menggunakan fitur pemindaian
                                    tanpa harus membuat akun. Hasil analisis
                                    merupakan indikasi berdasarkan model,
                                    bukan jaminan keamanan mutlak.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-4">
                            <div class="bg-white rounded-3 p-3 text-center border h-100">
                                <h6 class="fw-semibold mb-1">Mudah digunakan</h6>
                                <small class="text-secondary">
                                    Cukup masukkan URL.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="bg-white rounded-3 p-3 text-center border h-100">
                                <h6 class="fw-semibold mb-1">Machine Learning</h6>
                                <small class="text-secondary">
                                    Analisis karakteristik URL.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="bg-white rounded-3 p-3 text-center border h-100">
                                <h6 class="fw-semibold mb-1">Penjelasan hasil</h6>
                                <small class="text-secondary">
                                    Pahami faktor prediksi.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
