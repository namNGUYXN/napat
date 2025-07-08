@extends('layouts.app')

@section('title', 'Làm bài')

@section('content')
    <!-- Main Content -->

    <div class="col bg-light p-4">
        <form action="{{ route('bai-kiem-tra.nop-bai') }}" method="POST" class="row listQuestions custom-scrollbar">
            @csrf
            <input type="hidden" name="id_bai_kiem_tra" value="{{ $baiKiemTra->id }}">
            <!-- Left: Questions -->
            <div class="col-md-8">
                <div id="questions">
                    @foreach ($baiKiemTra->list_cau_hoi as $index => $item)
                        <div class="card mb-4 shadow-sm border-0 question-card rounded-4 p-3"
                            data-question="{{ $index + 1 }}" data-id-question="{{ $item->id }}">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-question-circle me-2"></i>
                                    Câu {{ $index + 1 }}: {{ $item->tieu_de }}
                                </h5>

                                <!-- Row với khoảng cách ngang giữa các đáp án -->
                                <div class="row row-cols-1 row-cols-md-2 gx-4 gy-3">
                                    <div class="col">
                                        <div class="form-check p-3 border rounded hover-effect">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $item->id }}]" value="A"
                                                id="q{{ $item->id }}a">
                                            <label class="form-check-label ms-2" for="q{{ $item->id }}a">
                                                {{ $item->dap_an_a }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check p-3 border rounded hover-effect">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $item->id }}]" value="B"
                                                id="q{{ $item->id }}b">
                                            <label class="form-check-label ms-2" for="q{{ $item->id }}b">
                                                {{ $item->dap_an_b }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check p-3 border rounded hover-effect">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $item->id }}]" value="C"
                                                id="q{{ $item->id }}c">
                                            <label class="form-check-label ms-2" for="q{{ $item->id }}c">
                                                {{ $item->dap_an_c }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-check p-3 border rounded hover-effect">
                                            <input class="form-check-input" type="radio"
                                                name="answers[{{ $item->id }}]" value="D"
                                                id="q{{ $item->id }}d">
                                            <label class="form-check-label ms-2" for="q{{ $item->id }}d">
                                                {{ $item->dap_an_d }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>



            <!-- Right: Navigation -->
            <div class="col-md-4 d-none d-md-block">
                <div class="question-nav">
                    <h6>Đã chọn: <span id="answeredCount">0</span> / <span id="totalQuestions"></span></h6>

                    <div id="questionNumbers">
                        @foreach ($baiKiemTra->list_cau_hoi as $index => $item)
                            <span class="question-number" data-jump="{{ $index + 1 }}">{{ $index + 1 }}</span>
                        @endforeach
                        <!-- Thêm câu khác -->
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <div>
                            <a href="{{ route('lop-hoc.detail', ['slug' => $baiKiemTra->lop_hoc_phan->slug]) }}"
                                class="btn btn-danger">Quay lại lớp</a>
                        </div>
                        <div>
                            <button class="btn btn-success">Nộp bài</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile sticky nav -->
            <div id="mobileNavToggle">Đã chọn: <span id="mobileCount">0</span> / <span id="mobileTotal">2</span> (Xem câu)
            </div>
            <div id="mobileQuestionNav">
                <div id="mobileQuestionNumbers">
                    <span class="question-number" data-jump="1">1</span>
                    <span class="question-number" data-jump="2">2</span>
                    <span class="question-number" data-jump="3">3</span>
                    <span class="question-number" data-jump="4">4</span>
                    <span class="question-number" data-jump="5">5</span>
                    <span class="question-number" data-jump="6">6</span>
                    <span class="question-number" data-jump="7">7</span>
                    <span class="question-number" data-jump="8">8</span>
                    <span class="question-number" data-jump="9">9</span>
                    <span class="question-number" data-jump="10">10</span>
                    <span class="question-number" data-jump="11">11</span>
                    <span class="question-number" data-jump="12">12</span>
                    <!-- Thêm câu khác -->
                </div>
            </div>
        </form>
    </div>

@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('modules/bai-tap/css/lam-bai.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('modules/bai-tap/js/lam-bai.js') }}"></script>
@endsection
