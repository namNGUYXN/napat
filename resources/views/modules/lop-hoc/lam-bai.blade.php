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
                                <div class="question-card" data-question="{{ $index + 1 }}" data-id-question="{{$item->id}}">
                                    <h6>Câu {{ $index + 1 }}: {{ $item->tieu_de }}</h6>
                                    <div>
                                        <div><input type="radio" name="answers[{{ $item->id }}]" value="A"> {{ $item->dap_an_a }}</div>
                                        <div><input type="radio" name="answers[{{ $item->id }}]" value="B"> {{ $item->dap_an_b }}</div>
                                        <div><input type="radio" name="answers[{{ $item->id }}]" value="C"> {{ $item->dap_an_c }}</div>
                                        <div><input type="radio" name="answers[{{ $item->id }}]" value="D"> {{ $item->dap_an_d }}</div>
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
                            <div class="text-end mt-4">
                                <button class="btn btn-success">Nộp bài</button>
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
