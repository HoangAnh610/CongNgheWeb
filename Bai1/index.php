<?php
include 'flowers.php';

$mode = isset($_GET['mod']) ? $_GET['mod'] : 'guest';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách các loài hoa - CSE485</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .card-img-top {
            height: 200px; 
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Danh sách Hoa - Công nghệ Web</h1>
        
        <div class="mb-4 text-center">
            <a href="index.php?mod=guest" class="btn btn-primary me-2">Giao diện Khách</a>
            <a href="index.php?mod=admin" class="btn btn-success">Giao diện Quản trị (Dạng bảng CRUD)</a>
        </div>

        <?php
        if ($mode == 'admin') {
            
            echo '<h3 class="mt-4 mb-3 text-success">📝 Chế độ Quản trị</h3>';
            echo '<a href="#" class="btn btn-sm btn-success mb-2">Thêm mới hoa</a>';
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped table-hover">';
            echo '<thead class="table-dark">
                    <tr>
                        <th>STT</th>
                        <th>Ảnh Chính</th>
                        <th>Tên Hoa</th>
                        <th>Mô Tả (Rút gọn)</th>
                        <th>Hành động (CRUD)</th>
                    </tr>
                  </thead>';
            echo '<tbody>';
            
            $stt = 1;
            foreach ($flowers as $flower) {
                echo '<tr>';
                echo '<td>' . $stt++ . '</td>';
                echo '<td><img src="' . $flower['image'][0] . '" alt="' . $flower['name'] . '" style="width: 80px; height: 80px; object-fit: cover;"></td>';
                echo '<td>' . $flower['name'] . '</td>';
                echo '<td>' . substr($flower['description'], 0, 80) . '...</td>'; 
                echo '<td>
                        <a href="#" class="btn btn-warning btn-sm my-1"><i class="bi bi-pencil-square"></i> Sửa</a>
                        <a href="#" class="btn btn-danger btn-sm my-1"><i class="bi bi-trash"></i> Xóa</a>
                      </td>';
                echo '</tr>';
            }
            
            echo '</tbody>';
            echo '</table>';
            echo '</div>'; 

        } else {
            echo '<h2 class="text-center mt-4 mb-5 text-dark">💐 14 loại hoa tuyệt đẹp thích hợp trồng để khoe hương sắc dịp xuân hè</h2>';
            
            echo '<div class="row justify-content-center">';
            echo '<div class="col-lg-9">';
            echo '<p class="lead text-muted mb-5">Mùa xuân hè là thời điểm lý tưởng để tô điểm cho không gian sống bằng những loài hoa rực rỡ. Dưới đây là danh sách 14 loài hoa nở đẹp nhất trong dịp này, được tổng hợp để bạn dễ dàng lựa chọn.</p>';

            foreach ($flowers as $flower) {
                
            
                echo '<h4 class="mt-5 mb-3 text-success border-bottom pb-2">' . $flower['name'] . '</h4>';
                echo '<figure class="text-center my-4">';
                echo '<img src="' . $flower['image'][0] . '" class="img-fluid rounded shadow-sm" alt="' . $flower['name'] . ' - Ảnh 1" style="max-height: 500px; object-fit: cover; width: 100%;">';
                echo '<figcaption class="figure-caption text-muted mt-2">Hình ảnh rực rỡ của hoa ' . $flower['name'] . '.</figcaption>';
                echo '</figure>';
                echo '<p class="text-dark fs-6">' . $flower['description'] . '</p>';
                echo '<figure class="text-center my-4">';
                echo '<img src="' . $flower['image'][1] . '" class="img-fluid rounded shadow-sm" alt="' . $flower['name'] . ' - Ảnh 2" style="max-height: 400px; object-fit: cover; width: 80%;">'; 
                echo '<figcaption class="figure-caption text-muted mt-2">Một góc nhìn khác về ' . $flower['name'] . '.</figcaption>';
                echo '</figure>';
                
                echo '<hr class="my-5">'; 
            }
            
            echo '</div>'; 
            echo '</div>'; 
        }
        ?>