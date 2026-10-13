<?php
// Public homepage. Authentication remains in the existing login pages.
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Saket College Student Attendance Management System information and authorized staff login.">
<title>Student Attendance Management System | Saket College</title>
<style>
:root{--navy:#12325b;--blue:#1d4ed8;--ink:#172033;--muted:#5b6577;--line:#dce4ef;--paper:#f5f8fc}
*{box-sizing:border-box}body{margin:0;font-family:Arial,Helvetica,sans-serif;color:var(--ink);background:var(--paper);line-height:1.6}
header{border-top:6px solid var(--blue);background:#fff;border-bottom:1px solid var(--line)}
.header-inner,main,.footer-inner{max-width:1080px;margin:auto;padding-left:20px;padding-right:20px}
.header-inner{padding-top:22px;padding-bottom:22px;display:flex;align-items:center;gap:16px}
.logo{width:58px;height:58px;flex:0 0 58px;border-radius:14px;display:grid;place-items:center;background:#e8efff;color:var(--navy);font-size:17px;font-weight:800;border:1px solid #cbd8fb }
.college{min-width:0}.college h1{font-size:clamp(17px,2.5vw,23px);line-height:1.3;margin:0;color:var(--navy)}
.college p{margin:5px 0 0;color:var(--muted);font-size:13px}
.college h1+p+p{align-items: center;}
main{padding-top:48px;padding-bottom:56px}.hero{display:grid;grid-template-columns:minmax(0,1.4fr) minmax(250px,.8fr);gap:28px;align-items:center}
.eyebrow{display:inline-block;padding:5px 10px;border-radius:999px;background:#e8efff;color:var(--navy);font-weight:700;font-size:12px;letter-spacing:.04em;text-transform:uppercase}
h2{color:var(--navy);font-size:clamp(30px,4.4vw,45px);line-height:1.12;margin:17px 0 16px}
.lead{color:var(--muted);font-size:17px;max-width:640px;margin:0 0 24px}
.actions{display:flex;flex-wrap:wrap;gap:12px}.btn{min-height:46px;padding:11px 18px;border-radius:9px;display:inline-flex;justify-content:center;align-items:center;text-decoration:none;font-weight:700;border:1px solid var(--blue);background:var(--blue);color:#fff}
.btn.secondary{background:#fff;color:var(--navy);border-color:#b8c6da}.btn:focus-visible,a:focus-visible{outline:3px solid #f59e0b;outline-offset:3px}
.info-card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:24px;box-shadow:0 10px 28px rgba(18,50,91,.06)}
.info-card h3{margin:0 0 12px;color:var(--navy);font-size:19px}.info-card ul{padding-left:20px;margin:0;color:var(--muted)}.info-card li+li{margin-top:8px}
.section{margin-top:42px}.section h3{color:var(--navy);font-size:23px;margin:0 0 12px}.section p{color:var(--muted);margin:0;max-width:800px}
.notice{margin-top:22px;padding:14px 16px;border-left:4px solid var(--blue);background:#edf3ff;border-radius:8px;color:#263d67;font-size:14px}
footer{border-top:1px solid var(--line);background:#fff}.footer-inner{padding-top:20px;padding-bottom:20px;display:flex;flex-wrap:wrap;justify-content:space-between;gap:10px;color:var(--muted);font-size:13px}
@media(max-width:720px){.header-inner{align-items:flex-start;padding-top:18px;padding-bottom:18px;padding-left:16px;padding-right:16px}.logo{width:46px;height:46px;flex-basis:46px;border-radius:11px}main{padding-top:32px;padding-bottom:42px;padding-left:16px;padding-right:16px}.hero{grid-template-columns:1fr}.info-card{padding:20px}}
</style>
</head>
<body>
<header><div class="header-inner">
<div class="logo" aria-hidden="true">SC</div>
<div class="college">
<h1>PROMOD RAM UJAGAR TIWARI SAKET INSTITUTE OF MANAGEMENT </h1>
<p>Govt.of Maharashtra & Affiliated to the University of Mumbai</p>
<p>NAAC Accredited 'B' Grade</p>
</div></div></header>
<main>
<section class="hero" aria-labelledby="page-title">
<div><span class="eyebrow"></span>
<h2 id="page-title">Student Attendance Management System</h2>
<p class="lead">This portal supports authorized college staff in recording student attendance and managing attendance records for academic lectures.</p>
<div class="actions"><a class="btn" href="teacher_login.php">Teacher Login</a><a class="btn secondary" href="admin_login.php">Admin Login</a></div>
</div>
<aside class="info-card" aria-label="Portal information"><h3>About this portal</h3>
<ul><li>Record attendance for scheduled lectures.</li><li>Review student attendance records.</li><li>Generate attendance reports where authorized.</li><li>Access is intended for authorized college staff.</li></ul>
</aside></section>
<section class="section" aria-labelledby="purpose-title"><h3 id="purpose-title">Purpose of the system</h3>
<p>The Student Attendance Management System is designed to help organize lecture-wise attendance records and make attendance reporting easier. Please use the login option that matches your authorized role.</p>
<div class="notice">Security notice: Never share your password. If you believe your account has been exposed, contact the system administrator and change it using a trusted, verified connection.</div>
</section></main>
<footer><div class="footer-inner"><span>© <?php echo date("Y"); ?> Saket Institute of Management & Education</span><span>Student Attendance Management System</span></div></footer>
</body></html>
