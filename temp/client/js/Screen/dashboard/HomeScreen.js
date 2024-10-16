import { fetchApi } from "../../api";
import { loading, select2 } from "../../util/util";
export const HomeScreen = {
    render() {

        const ctx = document.getElementById('myChart');
        const days_p = Object.keys(days);
        let labels = [];
        let data = [];
        let chart = [];  
        if (days_p.length>0) {
            for (let index = 0; index < days_p.length; index++) {
                labels.push(days[days_p[index]].p)
                data.push(days[days_p[index]].total_price)
            }
        }
        createChart(labels,data,30)
        select2().on('select2:select', function (e) {
            loading('#chart-box', 1);
            const _this = this;
            fetchApi('financial/designer_sell', 'GET', { params: { data: { days:this.value } } }).then(async (res) => {
                if (res.status) {
                    labels = [];
                    data   = [];
                    const days_item = Object.keys(res.data);
                    if (days_item.length>0) {
                        for (let index = 0; index < days_item.length; index++) {
                            labels.push(res.data[days_item[index]].p)
                            data.push(res.data[days_item[index]].total_price)
                        }
                    }
                    chart.destroy()
                    createChart(labels,data,_this.value)

                }
            }).catch(err => {
                console.log('e',err.message);
            }).finally(res => {
                loading('#chart-box', 0);

            })
        });
        function createChart(labels,data,days) {
            if (document.body.classList.contains('mobile-view')) {
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels ,
                        datasets: [{
                            label: days+' روز اخیر',
                            data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        backgroundColor: '#33d1e7',
                        borderRadius: 8,
                        scales: {
                            y: {
                                display: false
                            },
                            x: {
                                display: false
                            }
                        },
                        plugins: {
                            tooltip: {
                                cornerRadius: 8
                            },
                            legend: {
                                display: false,
                                labels: {
                                    color: 'rgb(255, 99, 132)'
                                }
                            }
                        }
                    }
                });
            }else{
                chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels ,
                        datasets: [{
                            label: '# هزار تومان',
                            data,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        backgroundColor: '#33d1e7',
                        borderRadius: 8,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            tooltip: {
                                cornerRadius: 8
                            }
                        }
                    }
                });
            }
        }
        if (document.querySelector('#modal_resterictions') != null) {
            const modal = new bootstrap.Modal(document.querySelector('#modalHiddenDesignerResterictions'));
            document.querySelector('#modal_resterictions').addEventListener('click', function () {  
              modal.show();   
            })
        }
    }
};