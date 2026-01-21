<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Print — Admin Report</title>
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
  <div class="print-title">Admin Report - Jobs & Projects</div>
  <div class="print-sub">Generated: <?= date('M d, Y h:i A') ?> • Timezone: Asia/Manila</div>

  <div class="box">
    <h6>Summary</h6>
    <table>
      <tr><th>Total Jobs</th><th>Jobs With Applicants</th><th>Total Client Projects</th><th>Projects With Applicants</th></tr>
      <tr>
        <td><?= (int)($total_jobs ?? 0) ?></td>
        <td><?= (int)($jobs_with_apps ?? 0) ?></td>
        <td><?= (int)($total_client_projects ?? 0) ?></td>
        <td><?= (int)($projects_with_apps ?? 0) ?></td>
      </tr>
    </table>
  </div>

  <div class="box">
    <h6>Jobs That Received Applicants (with names)</h6>
    <?php if (empty($jobs_applied)): ?>
      <div class="print-sub">No jobs with applicants yet.</div>
    <?php else: ?>
      <table>
        <thead><tr><th style="width:50px">ID</th><th>Job Title</th><th style="width:80px">Applicants</th><th>Applicant Names</th></tr></thead>
        <tbody>
          <?php foreach ($jobs_applied as $r):
            $jid = (int)$r['id'];
            $names = $jobApplicants[$jid]['names'] ?? [];
          ?>
          <tr>
            <td><?= $jid ?></td>
            <td><?= htmlspecialchars($r['title'] ?? '', ENT_QUOTES) ?></td>
            <td><?= (int)$r['applicant_count'] ?></td>
            <td><?= !empty($names) ? htmlspecialchars(implode('; ', $names), ENT_QUOTES) : '—' ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div class="print-sub" style="margin-top:6px">Names appear when available in the Users table.</div>
    <?php endif; ?>
  </div>

  <div class="box">
    <h6>Employers / Clients — Projects Summary</h6>
    <?php if (empty($clients_sum)): ?>
      <div class="print-sub">No client projects found.</div>
    <?php else: ?>
      <table>
        <thead><tr><th>Client</th><th style="width:120px">Total Projects</th><th style="width:140px">With Applicants</th></tr></thead>
        <tbody>
          <?php foreach ($clients_sum as $r):
            $cid   = (int)$r['clientID'];
            $label = $client_labels[$cid] ?? ('Client #'.$cid);
          ?>
          <tr>
            <td><?= htmlspecialchars($label, ENT_QUOTES) ?> (ID <?= $cid ?>)</td>
            <td><?= (int)$r['total_projects'] ?></td>
            <td><?= (int)$r['projects_with_apps'] ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <script>
    // Fire once, then close the tab after printing (prevents loops)
    window.addEventListener('load', () => {
      setTimeout(() => {
        window.print();
      }, 100);
    });
    window.addEventListener('afterprint', () => {
      // Give a moment so Chrome doesn't re-open preview
      setTimeout(() => window.close(), 100);
    });
  </script>
</body>
</html>


