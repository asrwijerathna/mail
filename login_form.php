<?php
// login_form.php
include('dbconnect.php');

// Fetch Institute Details
$inst_name = "Mail Management System";
$inst_logo = "img/300x60.png";
$curr_year = date("Y");

if(isset($conn)) {
    $sql_inst = "SELECT * FROM institute_details LIMIT 1";
    $res_inst = mysqli_query($conn, $sql_inst);
    if($res_inst && mysqli_num_rows($res_inst) > 0){
        $row_inst = mysqli_fetch_assoc($res_inst);
        $inst_name = $row_inst['institute_name'];
        $inst_logo = $row_inst['logo_path'];
    }
}
?>
<!DOCTYPE html>
<html lang="si">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($inst_name); ?> - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f0f4ff;
        }

        /* ── Left Panel ──────────────────────────────────── */
        .left-panel {
            width: 48%;
            background: linear-gradient(145deg, #0d1b6e 0%, #1a2fa8 50%, #2541c4 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 50px;
            position: relative;
            overflow: hidden;
        }

        /* decorative circles */
        .left-panel::before {
            content: '';
            position: absolute;
            width: 420px; height: 420px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.07);
            top: -100px; left: -100px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.07);
            bottom: -80px; right: -80px;
        }

        .left-panel .envelope-icon {
            font-size: 5rem;
            color: rgba(255,255,255,0.92);
            margin-bottom: 30px;
            filter: drop-shadow(0 8px 24px rgba(0,0,0,0.25));
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-10px); }
        }

        .left-panel h1 {
            color: #fff;
            font-size: 1.65rem;
            font-weight: 700;
            text-align: center;
            line-height: 1.4;
            letter-spacing: 0.3px;
        }
        .left-panel p {
            color: rgba(255,255,255,0.65);
            font-size: 0.9rem;
            margin-top: 14px;
            text-align: center;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .left-panel .divider {
            width: 50px; height: 3px;
            background: rgba(255,255,255,0.35);
            border-radius: 2px;
            margin: 22px auto;
        }

        /* ── Right Panel ─────────────────────────────────── */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 60px;
            background: #ffffff;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 28px;
        }
        .logo-wrap img {
            max-height: 70px;
            max-width: 220px;
            object-fit: contain;
        }

        .login-heading {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0d1b6e;
            text-align: center;
            margin-bottom: 6px;
        }
        .login-sub {
            font-size: 0.85rem;
            color: #8898b0;
            text-align: center;
            margin-bottom: 32px;
        }

        /* alert */
        .alert-box {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            font-weight: 500;
        }
        .alert-box.danger  { background: #fff0f0; color: #c0392b; border: 1px solid #fac8c8; }
        .alert-box.info    { background: #eef4ff; color: #1a2fa8; border: 1px solid #c5d5f7; }

        /* input fields */
        .field-group {
            position: relative;
            margin-bottom: 20px;
        }
        .field-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .field-group .input-wrap {
            display: flex;
            align-items: center;
            border: 1.8px solid #dde3f0;
            border-radius: 10px;
            background: #f8faff;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .field-group .input-wrap:focus-within {
            border-color: #1a2fa8;
            box-shadow: 0 0 0 3px rgba(26,47,168,0.1);
            background: #fff;
        }
        .field-group .input-wrap .icon {
            padding: 0 14px;
            color: #8898b0;
            font-size: 0.95rem;
            transition: color 0.25s;
        }
        .field-group .input-wrap:focus-within .icon {
            color: #1a2fa8;
        }
        .field-group input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            padding: 13px 14px 13px 0;
            font-size: 0.95rem;
            color: #1a202c;
            font-family: 'Inter', sans-serif;
        }
        .field-group input::placeholder { color: #a0aec0; }

        .toggle-pass {
            padding: 0 14px;
            cursor: pointer;
            color: #a0aec0;
            font-size: 0.9rem;
            transition: color 0.2s;
            background: none; border: none;
        }
        .toggle-pass:hover { color: #1a2fa8; }

        /* submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a2fa8 0%, #2541c4 100%);
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 6px 20px rgba(26,47,168,0.3);
            letter-spacing: 0.5px;
            margin-top: 8px;
        }
        .btn-login:hover {
            background: linear-gradient(135deg, #0d1b6e 0%, #1a2fa8 100%);
            box-shadow: 0 8px 28px rgba(26,47,168,0.4);
            transform: translateY(-1px);
        }
        .btn-login:active { transform: translateY(0); }
        .btn-login i { margin-left: 8px; }

        /* footer */
        .login-footer {
            margin-top: 36px;
            text-align: center;
            font-size: 0.78rem;
            color: #b0bec5;
        }

        /* ── Responsive ───────────────────────────────────── */
        @media (max-width: 820px) {
            .left-panel { display: none; }
            .right-panel { padding: 40px 28px; }
        }

        /* ── Feature List ─────────────────────────────────── */
        .features-list {
            margin-top: 36px;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 13px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-icon {
            flex-shrink: 0;
            width: 28px;
            text-align: center;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.5);
        }

        .feature-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .feature-text strong {
            color: rgba(255,255,255,0.92);
            font-size: 0.85rem;
            font-weight: 600;
        }
        .feature-text span {
            color: rgba(255,255,255,0.48);
            font-size: 0.75rem;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <!-- Left decorative panel -->
    <div class="left-panel">
        <i class="fas fa-envelope-open-text envelope-icon"></i>
        <h1>Department of Local Government<br>Mail Management</h1>
        <div class="divider"></div>
        <p>North Western Province</p>

        <div class="features-list">
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div class="feature-text">
                    <strong>ලිපි ලේඛනගත කිරීම</strong>
                    <span>ලැබෙන ලිපි (තැපෑල, Email, අතින් ලිපි ආදිය) ඉක්මනින් ලේඛනගත කරන්න</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div class="feature-text">
                    <strong>ලිපි නිරීක්ෂණය</strong>
                    <span>ලිපිවල වත්මන් තත්ත්වය (NON / REC / WOK / CLD) නිරීක්ෂණය කරන්න</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div class="feature-text">
                    <strong>කාර්යය සාධනය</strong>
                    <span>විෂය ලිපිකරුවන්ගේ කාර්යය ක්‍රියාකාරිත්වය නිරීක්ෂණය කරන්න</span>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><i class="fas fa-check"></i></div>
                <div class="feature-text">
                    <strong>වාර්තා හා සෙවීම</strong>
                    <span>ලිපි සෙවීම, CSV export සහ Professional PDF වාර්තා නිර්මාණය කරන්න</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right login panel -->
    <div class="right-panel">
        <div class="login-box">

            <!-- Logo -->
            <div class="logo-wrap">
                <img src="<?php echo htmlspecialchars($inst_logo); ?>" alt="<?php echo htmlspecialchars($inst_name); ?>">
            </div>

            <h2 class="login-heading">පද්ධතිගත ප්‍රවේශය</h2>
            <p class="login-sub"><?php echo htmlspecialchars($inst_name); ?></p>

            <!-- Alert -->
            <?php if(isset($_GET['invalid'])): ?>
                <div class="alert-box danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>පරිශීලක නාමය හෝ මුරපදය වැරදිය. නැවත උත්සාහ කරන්න.</span>
                </div>
            <?php else: ?>
                <div class="alert-box info">
                    <i class="fas fa-shield-alt"></i>
                    <span>ඔබගේ පරිශීලක නාමය සහ මුරපදය ඇතුලත් කරන්න.</span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="login_validation.php" method="post" name="mail_login" onsubmit="return validateForm()">

                <div class="field-group">
                    <label for="u_name">පරිශීලක නාමය</label>
                    <div class="input-wrap">
                        <span class="icon"><i class="fas fa-user"></i></span>
                        <input type="text" id="u_name" name="u_name"
                               placeholder="ඔබගේ user name ඇතුලත් කරන්න"
                               value="<?php echo isset($_GET['invalid']) ? htmlspecialchars($_GET['invalid']) : ''; ?>"
                               autocomplete="username">
                    </div>
                </div>

                <div class="field-group">
                    <label for="u_pass">මුරපදය</label>
                    <div class="input-wrap">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        <input type="password" id="u_pass" name="u_pass"
                               placeholder="ඔබගේ මුරපදය ඇතුලත් කරන්න"
                               autocomplete="current-password">
                        <button type="button" class="toggle-pass" onclick="togglePass()" title="Show/Hide Password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn-login">
                    ඇතුලත් කරන්න <i class="fas fa-arrow-right"></i>
                </button>

            </form>

            <div class="login-footer">
                &copy; <?php echo $curr_year; ?> Department of Local Government &mdash; NWP Mail System
            </div>
        </div>
    </div>

<script>
    function validateForm() {
        var x = document.forms["mail_login"]["u_name"].value;
        var y = document.forms["mail_login"]["u_pass"].value;
        if (x === "") {
            alert("කරුණාකර ඔබේ පරිශීලක නාමය ඇතුලත් කරන්න");
            return false;
        }
        if (y === "") {
            alert("කරුණාකර ඔබේ මුරපදය ඇතුලත් කරන්න");
            return false;
        }
        return true;
    }

    function togglePass() {
        var pw   = document.getElementById('u_pass');
        var icon = document.getElementById('toggleIcon');
        if (pw.type === 'password') {
            pw.type   = 'text';
            icon.classList.replace('fa-eye','fa-eye-slash');
        } else {
            pw.type   = 'password';
            icon.classList.replace('fa-eye-slash','fa-eye');
        }
    }

    // Auto-focus username field
    document.getElementById('u_name').focus();
</script>

</body>
</html>