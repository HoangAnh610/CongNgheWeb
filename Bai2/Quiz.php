<?php
// Tên file dữ liệu (Không cần ../ vì Quiz.txt và quiz.php cùng thư mục)
$file_path = 'Quiz.txt'; 
$quiz_data = []; 
$question_counter = 1; 

// --- 1. Đọc và xử lý file ---
if (file_exists($file_path)) {
    // Đảm bảo Quiz.txt có nội dung
    $lines = file($file_path, FILE_IGNORE_NEW_LINES);
    $current_question = null;

    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            if ($current_question !== null && !empty($current_question['answer'])) {
                $quiz_data[] = $current_question;
            }
            $current_question = null; 
            continue;
        }

        if ($current_question === null) {
            $current_question = [
                'id' => $question_counter++, 
                'question' => $line,
                'options' => [],
                'answer' => ''
            ];
            continue;
        }
        
        if (stripos($line, 'ANSWER:') === 0) {
            $current_question['answer'] = trim(substr($line, strlen('ANSWER:')));
        } 
        else if (preg_match('/^[A-D]\./', $line)) {
            $current_question['options'][] = $line;
        }
    }
    
    if ($current_question !== null && !empty($current_question['answer'])) {
        $quiz_data[] = $current_question;
    }
} else {
    die("Lỗi: Không tìm thấy file dữ liệu tại đường dẫn: " . $file_path . ". Vui lòng kiểm tra lại tên file hoặc đường dẫn.");
}

$total_questions = count($quiz_data);

// -------------------------------------------------------------
// --- 2. Giao diện hiển thị bài thi ---
// -------------------------------------------------------------
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Thi Trắc Nghiệm - CSE485</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .quiz-card { border-left: 5px solid #0d6efd; }
        .quiz-header { background-color: #e9f5ff; border-bottom: 1px solid #d0e4ff; }
        .quiz-option:hover { background-color: #f1f1f1; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4 text-primary"><i class="bi bi-patch-question-fill"></i> Bài Thi Trắc Nghiệm</h1>
        <p class="text-center text-muted">Tổng số câu hỏi: <?php echo $total_questions; ?></p>
        
        <form method="POST" action="quiz.php" onsubmit="return validateAndConfirm(<?php echo $total_questions; ?>);">
            <?php foreach ($quiz_data as $q): ?>
                <div class="card mb-4 shadow-sm quiz-card question-card">
                    <div class="card-header quiz-header">
                        <h5 class="mb-0 text-dark">Câu <?php echo $q['id']; ?>: <?php echo $q['question']; ?></h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($q['options'] as $option): 
                            $option_key = substr($option, 0, 1);
                        ?>
                            <label class="d-block py-2 quiz-option">
                                <input class="form-check-input me-2" type="radio" 
                                    name="answer_<?php echo $q['id']; ?>" 
                                    id="q<?php echo $q['id'] . $option_key; ?>" 
                                    value="<?php echo $option_key; ?>"
                                >
                                <?php echo $option; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="text-center my-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow"><i class="bi bi-check-circle-fill me-2"></i> Nộp Bài</button>
            </div>
        </form>

        <?php 
        // --- 3. Xử lý kết quả (Tính điểm và hiển thị) ---
        if ($_SERVER['REQUEST_METHOD'] == 'POST'): 
            $score = 0;
            echo '<div class="alert alert-info mt-5 p-4 shadow" role="alert">';
            echo '<h3><i class="bi bi-bar-chart-fill"></i> Kết Quả Bài Làm:</h3>';

            foreach ($quiz_data as $q) {
                $user_answer = $_POST['answer_' . $q['id']] ?? '';
                $is_correct = ($user_answer === $q['answer']);

                if ($is_correct) {
                    $score++;
                    echo '<p class="text-success">✅ Câu ' . $q['id'] . ': ĐÚNG. (Đáp án: ' . $q['answer'] . ')</p>';
                } else {
                    echo '<p class="text-danger">❌ Câu ' . $q['id'] . ': SAI. (Đáp án đúng: ' . $q['answer'] . ', Bạn chọn: ' . ($user_answer ? $user_answer : 'Chưa trả lời') . ')</p>';
                }
            }

            echo '<hr class="my-3">';
            echo '<h4 class="text-center">🏆 Tổng Điểm: ' . $score . ' / ' . $total_questions . '</h4>';
            echo '</div>';
        endif; 
        ?>

    </div>
    
    <script>
    function validateAndConfirm(totalQuestions) {
        let answeredCount = 0;
        
        // Đếm số câu hỏi đã được trả lời
        for (let i = 1; i <= totalQuestions; i++) {
            // Kiểm tra xem có radio button nào có tên 'answer_i' đã được chọn không
            const answered = document.querySelector(`input[name="answer_${i}"]:checked`);
            if (answered) {
                answeredCount++;
            }
        }

        // Nếu số câu trả lời nhỏ hơn tổng số câu hỏi
        if (answeredCount < totalQuestions) {
            const remaining = totalQuestions - answeredCount;
            const message = `Bạn còn ${remaining} câu hỏi chưa trả lời (${answeredCount}/${totalQuestions}).\n\nBạn có CHẮC CHẮN muốn nộp bài không?`;
            
            // Hiện hộp thoại xác nhận. Nếu người dùng nhấn OK (true), form sẽ nộp.
            return confirm(message); 
        }
        
        // Nếu đã trả lời hết, nộp bài bình thường
        return true;
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>