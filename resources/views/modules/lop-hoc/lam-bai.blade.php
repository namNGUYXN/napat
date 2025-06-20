@extends('layouts.app')

@section('title', 'Làm bài tập')

@section('content')
    <!-- Main Content -->
    <div class="col bg-light p-4">
        <div class="row listQuestions custom-scrollbar">
            <!-- Left: Questions -->
            <div class="col-md-8">
                <div id="questions">
                    <!-- Repeat for each question -->
                    <div class="question-card" data-question="1">
                        <h6>Câu 1: Thủ đô của Việt Nam là?</h6>
                        <div>
                            <div><input type="radio" name="q1" value="a"> A. TP.HCM</div>
                            <div><input type="radio" name="q1" value="b"> B. Hà Nội</div>
                            <div><input type="radio" name="q1" value="c"> C. Đà Nẵng</div>
                            <div><input type="radio" name="q1" value="d"> D. Huế</div>
                        </div>
                    </div>

                    <div class="question-card" data-question="2">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q2" value="a"> A. 3</div>
                            <div><input type="radio" name="q2" value="b"> B. 4</div>
                            <div><input type="radio" name="q2" value="c"> C. 5</div>
                            <div><input type="radio" name="q2" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="3">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q3" value="a"> A. 3</div>
                            <div><input type="radio" name="q3" value="b"> B. 4</div>
                            <div><input type="radio" name="q3" value="c"> C. 5</div>
                            <div><input type="radio" name="q3" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="4">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q4" value="a"> A. 3</div>
                            <div><input type="radio" name="q4" value="b"> B. 4</div>
                            <div><input type="radio" name="q4" value="c"> C. 5</div>
                            <div><input type="radio" name="q4" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="5">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q5" value="a"> A. 3</div>
                            <div><input type="radio" name="q5" value="b"> B. 4</div>
                            <div><input type="radio" name="q5" value="c"> C. 5</div>
                            <div><input type="radio" name="q5" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="6">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q6" value="a"> A. 3</div>
                            <div><input type="radio" name="q6" value="b"> B. 4</div>
                            <div><input type="radio" name="q6" value="c"> C. 5</div>
                            <div><input type="radio" name="q6" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="7">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q7" value="a"> A. 3</div>
                            <div><input type="radio" name="q7" value="b"> B. 4</div>
                            <div><input type="radio" name="q7" value="c"> C. 5</div>
                            <div><input type="radio" name="q7" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="8">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q8" value="a"> A. 3</div>
                            <div><input type="radio" name="q8" value="b"> B. 4</div>
                            <div><input type="radio" name="q8" value="c"> C. 5</div>
                            <div><input type="radio" name="q8" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="9">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q9" value="a"> A. 3</div>
                            <div><input type="radio" name="q9" value="b"> B. 4</div>
                            <div><input type="radio" name="q9" value="c"> C. 5</div>
                            <div><input type="radio" name="q9" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="10">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q10" value="a"> A. 3</div>
                            <div><input type="radio" name="q10" value="b"> B. 4</div>
                            <div><input type="radio" name="q10" value="c"> C. 5</div>
                            <div><input type="radio" name="q10" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="11">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q11" value="a"> A. 3</div>
                            <div><input type="radio" name="q11" value="b"> B. 4</div>
                            <div><input type="radio" name="q11" value="c"> C. 5</div>
                            <div><input type="radio" name="q11" value="d"> D. 6</div>
                        </div>
                    </div>
                    <div class="question-card" data-question="12">
                        <h6>Câu 2: 2 + 2 = ?</h6>
                        <div>
                            <div><input type="radio" name="q12" value="a"> A. 3</div>
                            <div><input type="radio" name="q12" value="b"> B. 4</div>
                            <div><input type="radio" name="q12" value="c"> C. 5</div>
                            <div><input type="radio" name="q12" value="d"> D. 6</div>
                        </div>
                    </div>
                    <!-- Thêm câu hỏi tương tự -->
                </div>
            </div>

            <!-- Right: Navigation -->
            <div class="col-md-4 d-none d-md-block">
                <div class="question-nav">
                    <h6>Đã chọn: <span id="answeredCount">0</span> / <span id="totalQuestions">2</span></h6>
                    <div id="questionNumbers">
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
                    <div class="text-end mt-4">
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
    </div>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('modules/bai-tap/css/lam-bai.css') }}">
@endsection

@section('scripts')
    <script src="{{ asset('modules/bai-tap/js/lam-bai.js') }}"></script>
@endsection
