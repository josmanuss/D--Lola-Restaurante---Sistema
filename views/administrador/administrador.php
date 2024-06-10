<div class="content-wrapper">
  <main>
    <div class="container-fluid px-4">
      <div class="header">
        <div class="col-sm-6 p-2">
            <h1 class="mb-3">Menú principal</h1>

            <h1>xdxx</h1>
        </div>
      </div>  
      <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <span style="float: left; margin-right: 5px;"><?php echo $data["cantidades"]["trabajador_activo"];?></span>
                    <i class="fas fa-users"></i>&nbsp;Trabajadores activos
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">Ver detalles</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                      <span style="float: left; margin-right: 5px;"><?php echo $data["cantidades"]['cliente'];?></span>
                      <i class="fas fa-user"></i>&nbsp;Clientes
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">Ver detalles</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                    <span style="float: left; margin-right: 5px;"><?php echo $data["cantidades"]['trabajador'];?></span>
                    <i class="fas fa-hard-hat"></i>&nbsp;Trabajadores</div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                      <a class="small text-white stretched-link" href="#">Ver detalles</a>
                      <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                  <div class="card-body"><span style="float: left; margin-right: 5px;"><?php echo $data["cantidades"]['categoria'];?></span>
                  <i class="fas fa-shopping-cart"></i>&nbsp;Categorias</div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                      <a class="small text-white stretched-link" href="#">Ver detalles</a>
                      <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6">
              <div class="card bg-primary text-white mb-4">
                  <div class="card-body"><span style="float: left; margin-right: 5px;"><?php echo $data["cantidades"]['plato'];?></span>
                  <i class="fas fa-wine-glass"></i>&nbsp;Platos</div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                      <a class="small text-white stretched-link" href="#">Ver detalles</a>
                      <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6">
              <div class="card bg-danger text-white mb-4">
                  <div class="card-body"><span style="float: left; margin-right: 5px;"><?php echo $data["cantidades"]["venta"];?></span>
                  <i class="fas fa-shopping-bag"></i>&nbsp;Total de ventas: </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                      <a class="small text-white stretched-link" href="#">Ver detalles</a>
                      <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                  </div>
              </div>
          </div>
          <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <span style="float: left;"><i class="fas fa-dollar-sign"></i>&nbsp;Ganancias</span> <!-- Texto -->
                    <span style="float: right; margin-left: 5px;">S/.<?php echo $data["cantidades"]['ganancia'];?></span> <!-- Número -->
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white stretched-link"></div>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

      </div>
      <div class="row">
          <div class="col-xl-6">
              <div class="card mb-4">
                  <div class="card-header">
                      <i class="fas fa-chart-area me-1"></i>
                      Productos mas vendidos
                  </div>
                  <div class="card-body"><canvas id="myBarChartPV" width="50%" height="45"></canvas></div>
              </div>
          </div>
          <div class="col-xl-6">
              <div class="card mb-4">
                  <div class="card-header">
                      <i class="fas fa-chart-bar me-1"></i>
                      Productos que necesitan reposición
                  </div>
                  <div class="card-body"><canvas id="myBarChartPR" width="100%" height="40"></canvas></div>
              </div>
          </div>
          <div class="col-xl-6">
              <div class="card">
                  <div class="card-header">
                      <i class="fas fa-chart-bar"></i>
                      Trabajadores por categoria
                  </div>
                  <div class="card-body"><canvas id="myBarChartTrabajador" width="100%"></canvas></div>
              </div>
          </div>
      </div>
    </div>
  </main>  
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="views/administrador/js/funciones.js"></script>