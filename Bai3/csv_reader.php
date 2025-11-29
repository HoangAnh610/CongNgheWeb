<?php

$file_path = 'accounts.csv';
$accounts = []; 
$headers = [];

if (file_exists($file_path)) {
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        
        if (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $headers = $data;
        }

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            
            if (count($data) === count($headers)) {
    
                $accounts[] = array_combine($headers, $data);
            }
        }
        
        fclose($handle);
    } else {
        die("Lỗi: Không thể mở file dữ liệu " . $file_path);
    }
} else {
    die("Lỗi: Không tìm thấy file dữ liệu " . $file_path);
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Tài Khoản (CSV) - CSE485</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .table-custom { border: 1px solid #dee2e6; }
        .table-custom thead th { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4 text-primary"><i class="bi bi-file-earmark-spreadsheet-fill"></i> Danh Sách Tài Khoản (Đọc từ CSV)</h1>
        <p class="text-center text-muted">Dữ liệu được đọc trực tiếp từ file <code>accounts.csv</code>.</p>
        
        <div class="table-responsive mt-5">
            <table class="table table-bordered table-striped table-hover table-custom align-middle">
                <thead>
                    <tr>
                        <?php foreach ($headers as $header): ?>
                            <th scope="col"><?php echo strtoupper($header); ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($accounts)): ?>
                        <?php foreach ($accounts as $account): ?>
                            <tr>
                                <td><?php echo $account['id']; ?></td>
                                <td><?php echo $account['username']; ?></td>
                                <td><?php echo $account['lastname']; ?></td>
                                <td><?php echo $account['firstname']; ?></td>
                                <td><?php echo $account['email']; ?></td>
                                <td>
                                    <span class="badge <?php echo ($account['role'] == 'admin' ? 'bg-danger' : 'bg-success'); ?>">
                                        <?php echo strtoupper($account['role']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="<?php echo count($headers); ?>" class="text-center text-danger">Không có dữ liệu tài khoản nào được tìm thấy.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>