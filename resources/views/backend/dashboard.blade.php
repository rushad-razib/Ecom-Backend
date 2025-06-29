@extends('layouts.admin')
@section('content')
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h5>Welcome to Dashboard</h5>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3>Total Sales</h3>
            </div>
            <div class="card-body">
              <div>
                <canvas id="myChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('jscript')
<script>
  const ctx = document.getElementById('myChart');
  var total_sales = {{Js::from($total_sales)}}
  var date = {{Js::from($date)}}
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: date,
      datasets: [{
        label: 'Total sales',
        data: total_sales,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endsection