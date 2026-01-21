$(function () {
  'use strict';
  if ($(".mapael-example-2").length) {
    $(".mapael-example-2").mapael({
      map: {
        name: "france_departments",
        defaultArea: {
          attrs: {
            fill: "#f4f4e8",
            stroke: "#00a1fe",
            "stroke-width": 0.8
          },
          attrsHover: {
            fill: "#a4e100",
            "stroke-width": 1.2
          }
        },
        defaultPlot: {
          type: "circle",
          attrs: {
            fill: "#00a1fe",
            stroke: "#FFFFFF",
            "stroke-width": 2,
            opacity: 0.8
          },
          attrsHover: {
            opacity: 1,
            "stroke-width": 3
          },
          size: 10
        }
      },
      legend: {
        area: {
          display: true,
          title: "Departments",
          slices: [{
            label: "Default",
            attrs: {
              fill: "#f4f4e8"
            }
          }, {
            label: "Hover",
            attrs: {
              fill: "#a4e100"
            }
          }]
        },
        plot: [{
          labelAttrs: {
            fill: "#6a6b83",
            "font-size": "12px"
          },
          titleAttrs: {
            fill: "#6a6b83",
            "font-weight": "bold",
            "font-size": "14px"
          },
          cssClass: 'population',
          mode: 'horizontal',
          title: "Population",
          marginBottomTitle: 10,
          slices: [{
            size: 15,
            attrs: {
              fill: '#00a1fe'
            },
            label: "< 10,000",
            max: 10000
          }, {
            size: 25,
            attrs: {
              fill: '#00a1fe'
            },
            label: "10,000 - 100,000",
            min: 10000,
            max: 100000
          }, {
            size: 35,
            attrs: {
              fill: '#00a1fe'
            },
            label: "> 100,000",
            min: 100000
          }]
        }]
      },
      plots: {
        'paris': {
          latitude: 48.8566,
          longitude: 2.3522,
          value: [2200000, 35],

        },
        'lyon': {
          latitude: 45.7640,
          longitude: 4.8357,
          value: [500000, 25],

        },
        'marseille': {
          latitude: 43.2965,
          longitude: 5.3698,
          value: [860000, 25],

        },
        'toulouse': {
          latitude: 43.6047,
          longitude: 1.4442,
          value: [470000, 25],

        },
        'nice': {
          latitude: 43.7102,
          longitude: 7.2620,
          value: [340000, 25],

        },
        'nantes': {
          latitude: 47.2184,
          longitude: -1.5536,
          value: [310000, 25],

        },
        'strasbourg': {
          latitude: 48.5734,
          longitude: 7.7521,
          value: [280000, 25],

        },
        'bordeaux': {
          latitude: 44.8378,
          longitude: -0.5792,
          value: [250000, 25],

        }
      },
      tooltip: {
        cssClass: "map-tooltip",
        position: "right",
        content: function (options, id, value, text) {
          return value.tooltip.content || "<b>" + id + "</b><br />Population: " + value.value[0];
        },
        effect: "fade",
        hide: {
          delay: 300
        }
      },
      zoom: {
        enabled: true,
        maxLevel: 10,
        animDuration: 300
      }
    });
  }
});