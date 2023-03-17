!function ($) {
  "use strict";

  var Dashboard = function (){

  };
      //creates area chart
      Dashboard.prototype.createAreaChart = function (element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
          Morris.Area({
              element: element,
              pointSize: 0,
              lineWidth: 0,
              data: data,
              xkey: xkey,
              ykeys: ykeys,
              labels: labels,
              resize: true,
              gridLineColor: '#eef0f2',
              hideHover: 'auto',
              lineColors: lineColors,
              fillOpacity: .9,
              behaveLikeLine: true
          });
      },

      Dashboard.prototype.init = function (data) {
          let tihis = this;
          var $areaData = [
              {y: '12. ay', a: 1, b: 1, c:1},
              {y: '2015', a: 60, b: 150, c:220},
              {y: '2016', a: 1, b: 1, c:1},
              {y: '2017', a: 1, b: 1, c:1},
              {y: '2018', a: 1, b: 1, c:1},
              {y: '2019', a: 1, b: 1, c:1}
          ];
          //console.log($areaData);
          console.log(data);
          //console.log(areavalue);                                       ['a','b','c']02c58d
          tihis.createAreaChart('morris-area-example', 0, 0, data, 'y', ['a'], ['Toplam Satış(TL)', 'Ödeme İsteği', 'Başarılı Ödeme'], ['#30419b', '#fcbe2d', '#02c58d']);
        console.log("islem tamam");
      },
      //init
      $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard

}(window.jQuery),

//initializing 
  function ($) {
    $.post( "settings/process.php", { post: "Dashboard-area"},function(data){
      //console.log(data);
      $.Dashboard.init(data);
    },"json");

    
  }(window.jQuery);