<div class="boards">
   <?php foreach ($metrics as $name => $val): ?>
   <div class="board-wrapper">
      <div class="board">
         <p class="board-title"><?= $name ?></p>
         <p class="board-content">
            <i class="fa fa-bar-chart fa-2x"></i>
            <span><?= $val > 0 ? '+'.$val : $val ?></span>
         </p>
      </div>
   </div>
   <?php endforeach ?>
</div>
<div class="chart-wrapper">
   <div class="chart">
      <canvas id="chart"></canvas>
   </div>
</div>