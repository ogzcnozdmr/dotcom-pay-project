/*
 Template Name: Stexo - Responsive Bootstrap 4 Admin Dashboard
 Author: Themesdesign
 File: Dashboard Init
 */


!function ($) {
  "use strict";

  var Dashboard = function (){
    console.log("geldi1");
  };

      //creates area chart
      Dashboard.prototype.createAreaChart = function (element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
        console.log("geldi2");
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

      //creates Donut chart
      Dashboard.prototype.createDonutChart = function (element, data, colors) {
        console.log("geldi3");
          Morris.Donut({
              element: element,
              data: data,
              resize: true,
              colors: colors
          });
      },
          //creates line chart Dark
    /*Dashboard.prototype.createLineChart1 = function(element, data, xkey, ykeys, labels, lineColors) {
      Morris.Line({
          element: element,
          data: data,
          xkey: xkey,
          ykeys: ykeys,
          labels: labels,
          gridLineColor: '#eef0f2',
          hideHover: 'auto',
          pointSize: 3,
          resize: true, //defaulted to true
          lineColors: lineColors
      });
  },*/


      Dashboard.prototype.init = function () {
        console.log("geldi4");
          let tihis = this;
          //let $areaData;
          /*$.post( "settings/process.php", { post: "Dashboard-area"}).done(function(data){
            var obj = jQuery.parseJSON(data);
            console.log(obj);
            

          });*/
          var $areaData = [
              {y: '2013', a: 0, b: 0, c:0},
              {y: '2014', a: 150, b: 45, c:15},
              {y: '2015', a: 60, b: 150, c:220},
              {y: '2016', a: 180, b: 36, c:21},
              {y: '2017', a: 90, b: 60, c:360},
              {y: '2018', a: 75, b: 240, c:120},
              {y: '2019', a: 30, b: 30, c:30}
          ];
          //console.log(areavalue);
          this.createAreaChart('morris-area-example', 0, 0, $areaData, 'y', ['a', 'b', 'c'], ['Toplam Satış', 'Ödeme İsteği', 'Başarılı Ödeme'], ['#fcbe2d', '#02c58d', '#30419b']);


          /*$.post( "settings/process.php", { post: "Dashboard-area"},function(data){
            console.log(data);
            //$areaData = data;
            //console.log($areaData);
            this.createAreaChart('morris-area-example', 0, 0, data, 'y', ['a', 'b', 'c'], ['Toplam Satış', 'Ödeme İsteği', 'Başarılı Ödeme'], ['#fcbe2d', '#02c58d', '#30419b']);
          },"json");*/
                  


          //creating area chart
          /*var $areaData = [
              {y: '2013', a: 0, b: 0, c:0},
              {y: '2014', a: 150, b: 45, c:15},
              {y: '2015', a: 60, b: 150, c:220},
              {y: '2016', a: 180, b: 36, c:21},
              {y: '2017', a: 90, b: 60, c:360},
              {y: '2018', a: 75, b: 240, c:120},
              {y: '2019', a: 30, b: 30, c:30}
          ];
          console.log($areaData);*/
          
          //creating donut chart
          var $donutData = [
              {label: "Download Sales", value: 12},
              {label: "In-Store Sales", value: 30},
              {label: "Mail-Order Sales", value: 20}
          ];
          this.createDonutChart('morris-donut-example', $donutData, ['#fcbe2d', '#30419b', '#02c58d']);

                  //create line chart Dark
        /*var $data1  = [
          { y: '2009', a: 20, b: 5 },
          { y: '2010', a: 45,  b: 35 },
          { y: '2011', a: 50,  b: 40 },
          { y: '2012', a: 75,  b: 65 },
          { y: '2013', a: 50,  b: 40 },
          { y: '2014', a: 75,  b: 65 },
          { y: '2015', a: 100, b: 90 }
      ];
      this.createLineChart1('morris-line-example', $data1, 'y', ['a', 'b'], ['Series A', 'Series B'], ['#30419b', '#02c58d']);*/



      },
      //init
      $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard







}(window.jQuery),

//initializing 
  function ($) {
      /*"use strict";
      $.post( "settings/process.php", { post: "Dashboard-area"}).done(function(data){
        var obj = jQuery.parseJSON(data);
        console.log(obj);
        $.Dashboard.init(obj);
      });*/
      $.Dashboard.init();
      console.log("geldi5");
      
  }(window.jQuery);