@extends('online.layouts.master')
@section('title','Kullanıcı Paneli')
@section('title-page','Anasayfa')
@section('content')
    <style>
        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #3fa288;
            border-color: #3fa288;
        }
        .page-link {
            color: black;
        }
        .tabs {
            display: flex;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .tab {
            padding: 10px 20px;
            border: 1px solid #ccc;
            border-bottom: none;
            background: #eee;
            margin-right: 5px;
            user-select: none;
        }
        .tab.active {
            background: white;
            font-weight: bold;
        }
        .tab-content {
            border: 1px solid #ccc;
            padding: 10px;
            display: none;
        }
        .tab-content.active {
            display: flex;
        }
        #tumChart {
            max-width: 400px;
            height: 300px !important;
        }
        #tum {
            justify-content: center;
            align-items: center;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="col-12 col-lg-9">
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stats-icon" style="background-color: orangered;">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h6 class="font-semibold">Kamera Sayısı</h6>
                                <h6 class="font-extrabold mb-0">{{$camerasCount}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <h4>Tespit Analizi</h4>

                    <div class="tabs">
                        <div class="tab active" data-tab="tum">Tümü</div>
                        <div class="tab" data-tab="kamera">Kamera Bazında</div>
                        <div class="tab" data-tab="hayvan">Hayvan Türü Bazında</div>
                    </div>

                    <div id="tum" class="tab-content active">
                        <canvas id="tumChart" width="400" height="300" style="max-width:100%; height:auto;"></canvas>
                    </div>


                    <div id="kamera" class="tab-content">
                        <canvas id="kameraChart" width="800" height="400"></canvas>
                    </div>

                    <div id="hayvan" class="tab-content">
                        <canvas id="hayvanChart" width="800" height="400"></canvas>
                    </div>
                    <div class="card-body">
                        <div id="chart-profile-visit"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-body py-4 px-5">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl">
                        <img src="{{asset('images')}}/admin.png" alt="Face 1">
                    </div>
                    <div class="ms-3 mt-2 name">
                        <h5 class="font-bold">{{$user->name}}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const tabs = document.querySelectorAll('.tab');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                contents.forEach(c => c.classList.remove('active'));
                tab.classList.add('active');
                document.getElementById(tab.dataset.tab).classList.add('active');
            });
        });
        async function drawTumChart() {
            const res = await fetch('{{route('action.user.dogruluk')}}');
            const data = await res.json();

            const ctx = document.getElementById('tumChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Doğru', 'Yanlış'],
                    datasets: [{
                        label: 'Tümü',
                        data: [data.dogru, data.yanlis],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 99, 132, 0.7)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        async function fetchKameraData() {
            const res = await fetch('{{route('action.user.kamera.dogruluk')}}');
            return res.json();
        }

        async function drawKameraChart() {
            const data = await fetchKameraData();

            const labels = data.map(d => `${d.camera_name}`);
            const dogru = data.map(d => d.dogru);
            const yanlis = data.map(d => d.yanlis);

            const ctx = document.getElementById('kameraChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doğru',
                            data: dogru,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)'
                        },
                        {
                            label: 'Yanlış',
                            data: yanlis,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }

        async function fetchHayvanData() {
            const res = await fetch('{{route('action.user.hayvan.dogruluk')}}');
            return res.json();
        }

        async function drawHayvanChart() {
            const data = await fetchHayvanData();

            const hayvanTipleri = {
                0: 'Köpek',
                1: 'Domuz',
                2: 'Güvercin'
            };

            const labels = data.map(d => hayvanTipleri[d.animal_type] || `Tip ${d.animal_type}`);
            const dogru = data.map(d => d.dogru);
            const yanlis = data.map(d => d.yanlis);

            const ctx = document.getElementById('hayvanChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Doğru',
                            data: dogru,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)'
                        },
                        {
                            label: 'Yanlış',
                            data: yanlis,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
        drawTumChart();
        drawKameraChart();
        drawHayvanChart();
    </script>
@endsection
@section('js')



@endsection
