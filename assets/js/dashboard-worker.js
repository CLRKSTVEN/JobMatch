(function () {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function parseJson(value, fallback) {
    if (!value) return fallback;
    try {
      return JSON.parse(value);
    } catch (e) {
      return fallback;
    }
  }

  function init() {
    renderPerfChart();
    initSvcMix();
  }

  function renderPerfChart() {
    var el = document.getElementById('workerPerfChart');
    if (!el || typeof Chart === 'undefined') return;

    var dataEl = document.getElementById('jmWorkerChartData');
    var labels = parseJson(el.dataset.labels || (dataEl && dataEl.dataset.labels), []);
    var values = parseJson(el.dataset.values || (dataEl && dataEl.dataset.values), []);

    if (!labels.length && !values.length) return;

    new Chart(el.getContext('2d'), {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Jobs',
          data: values,
          tension: 0.35,
          borderWidth: 2,
          borderColor: 'rgba(31,79,209,1)',
          fill: true,
          backgroundColor: function (ctx) {
            var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 180);
            g.addColorStop(0, 'rgba(31,79,209,.18)');
            g.addColorStop(1, 'rgba(31,79,209,.02)');
            return g;
          }
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          x: {
            grid: {
              display: false
            },
            ticks: {
              color: '#6B7280',
              font: {
                size: 11
              }
            }
          },
          y: {
            grid: {
              color: '#eef2f7'
            },
            ticks: {
              precision: 0,
              color: '#6B7280',
              font: {
                size: 11
              }
            }
          }
        }
      }
    });
  }

  async function initSvcMix() {
    var panel = document.getElementById('svcMixPanel');
    var canvas = document.getElementById('svcMixChart');
    if (!panel || !canvas || typeof Chart === 'undefined') return;

    var mixUrl = panel.dataset.mixUrl || '';
    if (!mixUrl) return;

    var pillsBox = document.getElementById('svcMixPills');
    var caption = document.getElementById('svcMixCaption');

    async function fetchMix(status) {
      var qs = status ? ('?status=' + encodeURIComponent(status)) : '';
      var res = await fetch(mixUrl + qs, {
        credentials: 'same-origin'
      });
      var j = await res.json().catch(function () {
        return null;
      });
      return (j && j.ok && j.items) ? j.items : {
        rows: [],
        total: 0
      };
    }

    function setCaption(kind) {
      if (!caption) return;
      var map = {
        completed: 'Share of completed jobs by skill',
        in_progress: 'Share of in-progress jobs by skill',
        any: 'Share of all hires by skill'
      };
      caption.textContent = map[kind] || 'Share of jobs by skill';
    }

    function legend(rows, total) {
      var box = document.getElementById('svcMixLegend');
      if (!box) return;
      var html = rows.map(function (r) {
        var pct = total ? Math.round((r.count / total) * 100) : 0;
        return '<div class="svc-mix-row"><strong>' + r.title + '</strong>: ' + pct + '% <span class="text-muted">(' + r.count + ')</span></div>';
      }).join('') || '<div class="text-muted">No data.</div>';
      box.innerHTML = html;
    }

    var chart;

    function drawPie(rows) {
      var labels = rows.map(function (r) {
        return r.title;
      });
      var values = rows.map(function (r) {
        return r.count;
      });
      if (chart) chart.destroy();
      chart = new Chart(canvas.getContext('2d'), {
        type: 'pie',
        data: {
          labels: labels,
          datasets: [{
            data: values
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function (ctx) {
                  var c = ctx.parsed;
                  var tot = values.reduce(function (a, b) { return a + b; }, 0) || 1;
                  var pct = Math.round((c / tot) * 100);
                  return ctx.label + ': ' + c + ' (' + pct + '%)';
                }
              }
            }
          }
        }
      });
    }

    var mixCompleted = await fetchMix('completed');
    var mixInProg = await fetchMix('in_progress');
    var mixAny = await fetchMix('any');

    var hasCompleted = (mixCompleted.total || 0) > 0;
    var hasInProg = (mixInProg.total || 0) > 0;

    var current = hasCompleted ? 'completed' : (hasInProg ? 'in_progress' : 'any');
    var dataMap = {
      completed: mixCompleted,
      in_progress: mixInProg,
      any: mixAny
    };

    function makePill(kind, label, count) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'btn btn-light btn-sm';
      btn.innerHTML = label + (typeof count === 'number' ? ' <span class="text-muted">(' + count + ')</span>' : '');
      btn.dataset.kind = kind;
      btn.addEventListener('click', function () {
        current = kind;
        setActivePill();
        var d = dataMap[kind];
        setCaption(kind);
        drawPie(d.rows);
        legend(d.rows, d.total);
      });
      return btn;
    }

    function setActivePill() {
      if (!pillsBox) return;
      pillsBox.querySelectorAll('button').forEach(function (b) {
        b.classList.toggle('btn-brand', b.dataset.kind === current);
        b.classList.toggle('btn-light', b.dataset.kind !== current);
      });
    }

    if (pillsBox) {
      pillsBox.innerHTML = '';
    }
    var pills = [];

    if (hasCompleted && hasInProg) {
      pills.push(makePill('completed', 'Completed', mixCompleted.total));
      pills.push(makePill('in_progress', 'In progress', mixInProg.total));
      pills.push(makePill('any', 'All hires', mixAny.total));
    }

    if (pills.length && pillsBox) {
      pills.forEach(function (p) {
        pillsBox.appendChild(p);
      });
      pillsBox.style.display = 'flex';
      setActivePill();
    } else if (pillsBox) {
      pillsBox.style.display = 'none';
    }

    setCaption(current);
    var d0 = dataMap[current];
    drawPie(d0.rows);
    legend(d0.rows, d0.total);
  }
})();
