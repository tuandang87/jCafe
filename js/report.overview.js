//**************** OVERVIEW CONTROLLER ********************//

app.controller('lineChartController', ['$rootScope', '$scope', '$interval', '$http', '$cookies', '$timeout', '$location', 'appLibs'

	, function ($rootScope, $scope, $interval, $http, $cookies, $timeout, $location, appLibs) {
		
	const CHART = document.getElementById("lineChart");
    Chart.defaults.global.animation.duration=200;
    Chart.defaults.global.responsive=true;
    Chart.defaults.scale.ticks.beginAtZero=true;
    // $dt1=0;$dt2=0;$dt3=0;$dt4=0;$dt5=0;$dt6=0;$dt7=0;
    // $hd1=0;$hd2=0;$hd3=0;$hd4=0;$hd5=0;$hd6=0;$hd7=0;
    // $hd1=0;$hd2=0;$hd3=0;$hd4=0;$hd5=0;$hd6=0;$hd7=0;
     $day=["","","","","","",""];
     $dt=[0,0,0,0,0,0,0];
     $sly=[0,0,0,0,0,0,0];
     $hd=[0,0,0,0,0,0,0];
     $sll=[0,0,0,0,0,0,0];

	$http.post('api/report.api.php', JSON.stringify({
            Username: 'admin'
            , Password: 'e10adc3949ba59abbe56e057f20f883e'
            , AccessLevel: '1'
            , Command: 'getdata'
            })).success(function (data, status, headers, config) {
             console.log("PP");
             console.log(data.SLL);
             $i=data.Reports.length;
             switch($i)
             {
                case 1: console.log("1");
                        $dt[0]=data.Reports[0].NumTotal/1000;
                        $day[0]=data.Reports[0].Day;
                        $sly[0]=data.Reports[0].NumLy;
                        $hd[0]=data.Reports[0].NumHD;
                        $sll[0]=data.SLL[0].SLLy;
                        break;
                case 2: console.log("2");
                        $dt[0]=data.Reports[1].NumTotal/1000;
                        $day[0]=data.Reports[1].Day;
                        $sly[0]=data.Reports[1].NumLy;
                        $hd[0]=data.Reports[1].NumHD;
                        $sll[0]=data.SLL[1].SLLy;
                        $dt[1]=data.Reports[0].NumTotal/1000;
                        $day[1]=data.Reports[0].Day;
                        $sly[1]=data.Reports[0].NumLy;
                        $hd[1]=data.Reports[0].NumHD;
                        $sll[1]=data.SLL[0].SLLy;
                        break;
                case 3: console.log("3");
                        $dt[0]=data.Reports[2].NumTotal/1000;
                        $day[0]=data.Reports[2].Day;
                        $sly[0]=data.Reports[2].NumLy;
                        $hd[0]=data.Reports[2].NumHD;
                        $sll[0]=data.SLL[2].SLLy;
                        $dt[1]=data.Reports[1].NumTotal/1000;
                        $day[1]=data.Reports[1].Day;
                        $sly[1]=data.Reports[1].NumLy;
                        $hd[1]=data.Reports[1].NumHD;
                        $sll[1]=data.SLL[1].SLLy;
                        $dt[2]=data.Reports[0].NumTotal/1000;
                        $day[2]=data.Reports[0].Day;
                        $sly[2]=data.Reports[0].NumLy;
                        $hd[2]=data.Reports[0].NumHD;
                        $sll[2]=data.SLL[0].SLLy;
                        break;
                case 4: console.log("4");
                        $dt[0]=data.Reports[3].NumTotal/1000;
                        $day[0]=data.Reports[3].Day;
                        $sly[0]=data.Reports[3].NumLy;
                        $hd[0]=data.Reports[3].NumHD;
                        $sll[0]=data.SLL[3].SLLy;
                        $dt[1]=data.Reports[2].NumTotal/1000;
                        $day[1]=data.Reports[2].Day;
                        $sly[1]=data.Reports[2].NumLy;
                        $hd[1]=data.Reports[2].NumHD;
                        $sll[1]=data.SLL[2].SLLy;
                        $dt[2]=data.Reports[1].NumTotal/1000;
                        $day[2]=data.Reports[1].Day;
                        $sly[2]=data.Reports[1].NumLy;
                        $hd[2]=data.Reports[1].NumHD;
                        $sll[2]=data.SLL[1].SLLy;
                        $dt[3]=data.Reports[0].NumTotal/1000;
                        $day[3]=data.Reports[0].Day;
                        $sly[3]=data.Reports[0].NumLy;
                        $hd[3]=data.Reports[0].NumHD;
                        $sll[3]=data.SLL[0].SLLy;
                        break;
                case 5: console.log("5");
                        $dt[0]=data.Reports[4].NumTotal/1000;
                        $day[0]=data.Reports[4].Day;
                        $sly[0]=data.Reports[4].NumLy;
                        $hd[0]=data.Reports[4].NumHD;
                        $sll[0]=data.SLL[4].SLLy;
                        $dt[1]=data.Reports[3].NumTotal/1000;
                        $day[1]=data.Reports[3].Day;
                        $sly[1]=data.Reports[3].NumLy;
                        $hd[1]=data.Reports[3].NumHD;
                        $sll[1]=data.SLL[3].SLLy;
                        $dt[2]=data.Reports[2].NumTotal/1000;
                        $day[2]=data.Reports[2].Day;
                        $sly[2]=data.Reports[2].NumLy;
                        $hd[2]=data.Reports[2].NumHD;
                        $sll[2]=data.SLL[2].SLLy;
                        $dt[3]=data.Reports[1].NumTotal/1000;
                        $day[3]=data.Reports[1].Day;
                        $sly[3]=data.Reports[1].NumLy;
                        $hd[3]=data.Reports[1].NumHD;
                        $sll[3]=data.SLL[1].SLLy;
                        $dt[4]=data.Reports[0].NumTotal/1000;
                        $day[4]=data.Reports[0].Day;
                        $sly[4]=data.Reports[0].NumLy;
                        $hd[4]=data.Reports[0].NumHD;
                        $sll[4]=data.SLL[0].SLLy;
                        break;
                case 6: console.log("6");
                        $dt[0]=data.Reports[5].NumTotal/1000;
                        $day[0]=data.Reports[5].Day;
                        $sly[0]=data.Reports[5].NumLy;
                        $hd[0]=data.Reports[5].NumHD;
                        $sll[0]=data.SLL[5].SLLy;
                        $dt[1]=data.Reports[4].NumTotal/1000;
                        $day[1]=data.Reports[4].Day;
                        $sly[1]=data.Reports[4].NumLy;
                        $hd[1]=data.Reports[4].NumHD;
                        $sll[1]=data.SLL[4].SLLy;
                        $dt[2]=data.Reports[3].NumTotal/1000;
                        $day[2]=data.Reports[3].Day;
                        $sly[2]=data.Reports[3].NumLy;
                        $hd[2]=data.Reports[3].NumHD;
                        $sll[2]=data.SLL[3].SLLy;
                        $dt[3]=data.Reports[2].NumTotal/1000;
                        $day[3]=data.Reports[2].Day;
                        $sly[3]=data.Reports[2].NumLy;
                        $hd[3]=data.Reports[2].NumHD;
                        $sll[3]=data.SLL[2].SLLy;
                        $dt[4]=data.Reports[1].NumTotal/1000;
                        $day[4]=data.Reports[1].Day;
                        $sly[4]=data.Reports[1].NumLy;
                        $hd[4]=data.Reports[1].NumHD;
                        $sll[4]=data.SLL[1].SLLy;
                        $dt[5]=data.Reports[0].NumTotal/1000;
                        $day[5]=data.Reports[0].Day;
                        $sly[5]=data.Reports[0].NumLy;
                        $hd[5]=data.Reports[0].NumHD;
                        $sll[5]=data.SLL[0].SLLy;
                        break;
                case 7: console.log("7");
                        $dt[0]=data.Reports[6].NumTotal/1000;
                        $day[0]=data.Reports[6].Day;
                        $sly[0]=data.Reports[6].NumLy;
                        $hd[0]=data.Reports[6].NumHD;
                        $sll[0]=data.SLL[6].SLLy;
                        $dt[1]=data.Reports[5].NumTotal/1000;
                        $day[1]=data.Reports[5].Day;
                        $sly[1]=data.Reports[5].NumLy;
                        $hd[1]=data.Reports[5].NumHD;
                        $sll[1]=data.SLL[5].SLLy;
                        $dt[2]=data.Reports[4].NumTotal/1000;
                        $day[2]=data.Reports[4].Day;
                        $sly[2]=data.Reports[4].NumLy;
                        $hd[2]=data.Reports[4].NumHD;
                        $sll[2]=data.SLL[4].SLLy;
                        $dt[3]=data.Reports[3].NumTotal/1000;
                        $day[3]=data.Reports[3].Day;
                        $sly[3]=data.Reports[3].NumLy;
                        $hd[3]=data.Reports[3].NumHD;
                        $sll[3]=data.SLL[3].SLLy;
                        $dt[4]=data.Reports[2].NumTotal/1000;
                        $day[4]=data.Reports[2].Day;
                        $sly[4]=data.Reports[2].NumLy;
                        $hd[4]=data.Reports[2].NumHD;
                        $sll[4]=data.SLL[2].SLLy;
                        $dt[5]=data.Reports[1].NumTotal/1000;
                        $day[5]=data.Reports[1].Day;
                        $sly[5]=data.Reports[1].NumLy;
                        $hd[5]=data.Reports[1].NumHD;
                        $sll[5]=data.SLL[1].SLLy;
                        $dt[6]=data.Reports[0].NumTotal/1000;
                        $day[6]=data.Reports[0].Day;
                        $sly[6]=data.Reports[0].NumLy;
                        $hd[6]=data.Reports[0].NumHD;
                        $sll[6]=data.SLL[0].SLLy;
                        break;
             }
             console.log(data.Reports);
             let lineChart=new Chart(CHART,{
    type: 'bar',
    data: {
    labels: [$day[0], $day[1], $day[2], $day[3], $day[4], $day[5],$day[6]],
    datasets: [
        {
            label: "Doanh thu",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#FF0000",
            borderColor: "rgba(75,192,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [$dt[0],$dt[1],$dt[2],$dt[3],$dt[4],$dt[5],$dt[6]],
            spanGaps: false,
        },
        {
            label: "Phụ thu",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#FFCC00",
            borderColor: "rgba(75,72,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,72,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,72,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [$sly[0],$sly[1],$sly[2],$sly[3],$sly[4],$sly[5],$sly[6]],
            spanGaps: true,

        },
        {
            label: "Số Ly",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#FF3399",
            borderColor: "rgba(75,72,192,1)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(75,72,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,72,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [$sll[0],$sll[1],$sll[2],$sll[3],$sll[4],$sll[5],$sll[6]],
            spanGaps: true,

        },
        {
            label: "Hóa Đơn",
            fill: false,
            lineTension: 0.1,
            backgroundColor: "#00FF00",
            borderColor: "rgba(10,10,10,10)",
            borderCapStyle: 'butt',
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: 'miter',
            pointBorderColor: "rgba(55,102,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(55,102,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10,
            data: [$hd[0],$hd[1],$hd[2],$hd[3],$hd[4],$hd[5],$hd[6]],
            spanGaps: false,
        }
    ]
       }

    });

        }).error(function (data, status, headers, config) {
            console.log("Error Connection: Get Receipt");
        });
  
    


		}]);
