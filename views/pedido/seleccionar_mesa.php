<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?php echo $data["titulo"]; ?></h1>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php foreach($data["mesa"] as $mesas): 
                    if ($mesas["estado"] !== "OCUPADA"):
                    ?>
                    <div class="col-lg-4 col-md-6 col-12"> 
                        <div class="small-box bg-gray">
                            <div class="inner">
                                <h3><?php echo $mesas["id_mesa"]; ?></h3>
                                <p>Mesa</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <?php if ($mesas["estado"] === "OCUPADA"): ?>
                                <div class="fas fa-arrow-circle-right"></div>
                            <?php else: ?>  
                                <a href="index.php?c=PedidoController&a=realizarPedido&id=<?php echo $mesas["id_mesa"]; ?>" class="small-box-footer">VER MAS&nbsp;<i class="fas fa-arrow-circle-right"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
