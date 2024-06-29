<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Administración</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
//hola
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
            <?php foreach($data["mesa"] as $mesas): ?>
                <div class="col-lg-4 col-md-6 col-12"> <!-- Cambiado el tamaño de las columnas -->
                <!-- small box -->
                <div class="small-box bg-gray">
                    <div class="inner">
                    <h3><?php echo $mesas["id_mesa"];?></h3>
                    <p>Mesa</p>
                    </div>
                    <div class="icon">
                    <i class="fas fa-utensils"></i>
                    </div>
                    <a href="index.php?c=PedidoController&a=realizarPedido&id=<?php echo $mesas["id_mesa"]; ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
                </div>
            <?php endforeach;?>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <!-- /.content -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
