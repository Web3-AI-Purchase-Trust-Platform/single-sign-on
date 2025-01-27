<?php
    require_once "../private/service/mailSender.php";
    require_once "../private/database/userData.php";
    require_once "../private/service/configLoader.php";

    $smtp = checkSmtpConnection();
    $redirect = jsonLoader::getConfig("redirect_url");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Status</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            width: 97vw;
            flex-direction: column;
            align-items: center;
        }
        .status-card {
            margin-top: 20px;
            width: 80%;
            max-width: 600px;
        }
    </style>
</head>
<body
    smtp = <?php echo $smtp ?>
    db   = <?php echo $db ?>
>
    <h2 style="margin-top: 35px">Single-Sign-On Trạng Thái Dịch Vụ</h2>

    <!-- SMTP Status -->
    <div class="card status-card">
        <div class="card-header">
            Kết nối tới mail server
        </div>
        <div class="card-body" id="smtp-status">
            <p>Lỗi javascript...</p>
        </div>
    </div>

    <!-- Database Status -->
    <div class="card status-card">
        <div class="card-header">
            Kết nối tới database
        </div>
        <div class="card-body" id="db-status">
            <p>Lỗi javascript...</p>
        </div>
    </div>

    <!-- Database Status -->
    <div class="card status-card">
        <div class="card-header">
            Các ứng dụng đang dùng dịch vụ này
        </div>
        <div class="card-body" id="db-status">
            <?php 
                if ($redirect && is_array($redirect)) {
                    // In các URL ra
                    echo "<ul>";
                    foreach ($redirect as $url) {
                        echo "<li><a href='$url'>$url</a></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "Không có URL để hiển thị.";
                }
            ?>
        </div>
    </div>

    <script>
        function updateSmtpStatus() {
            const smtpStatusElement = document.getElementById('smtp-status');
            const smtp_status = document.body.getAttribute('smtp')

            if(smtp_status === "1") {
                smtpStatusElement.innerHTML = `
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 10px
                    ">
                        <img src="https://cdn-icons-png.flaticon.com/128/16322/16322725.png" width="25px" height="25px" alt="">
                        <strong>Kết nối thành công</strong>
                    </div>
                `;
            } else {
                smtpStatusElement.innerHTML = `
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 10px
                    ">
                        <img src="https://cdn-icons-png.flaticon.com/128/16206/16206622.png" width="25px" height="25px" alt="">
                        <strong>Kết nối thất bại</strong>
                    </div>
                `;
            }
        }

        function updateDbStatus() {
            const DbStatusElement = document.getElementById('db-status');
            const Db_status = document.body.getAttribute('db')

            console.log(Db_status);

            if(Db_status === "1") {
                DbStatusElement.innerHTML = `
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 10px
                    ">
                        <img src="https://cdn-icons-png.flaticon.com/128/16322/16322725.png" width="25px" height="25px" alt="">
                        <strong>Kết nối thành công</strong>
                    </div>
                `;
            } else {
                DbStatusElement.innerHTML = `
                    <div style="
                        display: flex;
                        align-items: center;
                        gap: 10px
                    ">
                        <img src="https://cdn-icons-png.flaticon.com/128/16206/16206622.png" width="25px" height="25px" alt="">
                        <strong>Kết nối thất bại</strong>
                    </div>
                `;
            }
        }

        updateSmtpStatus()
        updateDbStatus()
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


