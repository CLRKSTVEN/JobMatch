<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Print — Client Projects</title>
  <style>
    :root{ --ink:#1e3a8a; --muted:#6b7280; --bd:#e5e7eb; }
    body{ font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial; font-size:13px; color:#0f172a; }
    .print-title{ font-weight:800; font-size:16px; color:var(--ink); margin:0 0 6px }
    .print-sub{ color:var(--muted); font-size:12px; margin:0 0 10px }
    .box{ border:1px solid var(--bd); border-radius:8px; padding:10px; margin:0 0 10px }
    .box h6{ margin:0 0 8px; font-size:13px; font-weight:800; color:var(--ink) }
    table{ width:100%; border-collapse: collapse }
    th,td{ border:1px solid var(--bd); padding:6px 8px; vertical-align:top; font-size:12.5px }
    @page { margin: 16mm; }
  </style>
</head>
<body>
  <div class="print-title">Client Projects - Detailed Applicants</div>
  <div class="print-sub">
    Client: <?= htmlspecialchars($client_label ?? ('Client #'.(int)$clientID), ENT_QUOTES) ?> (ID <?= (int)$clientID ?>) •
    Generated: <?= date('M d, Y h:i A') ?> • Timezone: Asia/Manila
  </div>

  <div class="box">
    <h6>Projects</h6>
    <?php if (empty($projects)): ?>
      <div class="print-sub">No projects found for this client.</div>
    <?php else: ?>
      <table>
        <thead><tr><th style="width:50px">ID</th><th>Project Title</th><th style="width:90px">Applicants</th><th>Applicant Names</th></tr></thead>
        <tbody>
          <?php foreach ($projects as $p):
            $pid   = (int)$p['id'];
            $names = $projectApplicants[$pid]['names'] ?? [];
          ?>
          <tr>
            <td><?= $pid ?></td>
            <td><?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES) ?></td>
            <td><?= (int)$p['applicant_count'] ?></td>
            <td><?= !empty($names) ? htmlspecialchars(implode('; ', $names), ENT_QUOTES) : '—' ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <script>
    window.addEventListener('load', () => {
      setTimeout(() => { window.print(); }, 100);
    });
    window.addEventListener('afterprint', () => {
      setTimeout(() => window.close(), 100);
    });
  </script>
</body>
</html>


