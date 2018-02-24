<?php 
ob_start();
  require_once '../core/init.php';
 
 if(!login_in()){
 	header('LOCATION: login.php');
 	}
  include 'includes/bheader.php';
  include 'includes/bnavbar.php';
?>
<?php
  $queryjoin = "SELECT t.ID, t.cart_id, t.full_name, t.description, t.grand_total, t.txn_date, c.items, c.paid, c.shipped
                  FROM transactions t
                  LEFT JOIN cart c ON t.cart_id = c.ID
                  WHERE c.paid = 1 AND c.shipped = 0
                  ORDER BY t.txn_date";
  $result = $db->query($queryjoin);               
 
 ?>   
 <div class="col-md-12">
   <h3 class="text-center">Order To Ship</h3>
   <table class="table table-bordered table-condensed table-striped">
      <thead>
      	<th></th>
      	<th>name</th>
      	<th>description</th>
      	<th>totale</th>
      	<th>date</th>
      </thead>
      <tbody>
      <?php while($join = mysqli_fetch_assoc($result)): ?>
      	<tr>
      		<td><a href="chargers.php?txn_id=<?=$join['ID']; ?>" class="btn btn-xs btn-info">details</a></td>
      		<td><?=$join['full_name'];?></td>
      		<td><?=$join['description'];?></td>
      		<td><?=mony($join['grand_total']); ?></td>
      		<td><?=pretty($join['txn_date']);?></td>
      	</tr>
      <?php endwhile; ?>
      </tbody>
   </table>
 </div>
 <div class="row">
  <?php
     $thisyr = date("Y");
     $lastyr = $thisyr - 1;
     $thisyrq = $db->query("SELECT grand_total,txn_date FROM transactions WHERE YEAR(txn_date) = '{$thisyr}'");
     $lastyrq = $db->query("SELECT grand_total,txn_date FROM transactions WHERE YEAR(txn_date) = '{$lastyr}'");
     $current = array();
     $last = array();
     $currenttotal = 0;
     $lasttotal  =  0;
     while($x = mysqli_fetch_assoc($thisyrq)){
        $month = date("m", strtotime($x['txn_date']));
        if(!array_key_exists($month, $current)){
            $current[(int)$month] = $x['grand_total']; 
        }else{
           $current[(int)$month]  += $x['grand_total'];
        }
        $currenttotal +=  $x['grand_total'];
     }
     while($y = mysqli_fetch_assoc($lastyrq)){
        $month = date('m', $y['txn_date']);
        if(!array_key_exists($month, $last)){
            $last[(int)$month] = $y['grand_total']; 
        }else{
           $last[(int)$month]  += $y['grand_total'];
        }
        $lasttotal +=  $y['grand_total'];
     }

  ?>
  <div class="col-md-4">
    <h3 class="text-center">Seling Month</h3>
    <table class="table table-bordered table-condensed table-striped">
      <thead>
        <th></th>
        <th><?=$lastyr; ?></th>
        <th><?=$thisyr; ?></th>
      </thead>
      <tbody>
        <?php
           for($i = 1; $i <= 12; $i++):
             $dt = DateTime::createfromformat("!m",$i);
        ?>
        <tr <?=(date('m') == $i)?' class="info"':''; ?>>
          <td><?=$dt->format('F'); ?></td>
          <td><?=(array_key_exists($i, $last))?mony($last[$i]):mony(0); ?></td>
          <td><?=(array_key_exists($i, $current))?mony($current[$i]):mony(0); ?></td>
        </tr>
      <?php endfor; ?>
      <tr>
        <td>Total</td>
        <td><?=mony($lasttotal); ?></td>
        <td><?=mony($currenttotal); ?></td>
      </tr>
      </tbody>
    </table>
  </div>
  <!-- treger size end trashild -->
  <?php
     $iquery = $db->query("SELECT * FROM product WHERE Delited = 0");
     $itemsizes = array();
     while($products = mysqli_fetch_assoc($iquery)){
         $items = array();
         $sizes = sizeToArray($products['size']);
         foreach ($sizes as $size) {
          if($size['quantity'] <= $size['threshold']){
           $cat = getcategory($products['Categorie']);
           $items = array(
            'title' => $products['Title'],
            'size' => $size['size'],
            'quantity' => $size['quantity'],
            'threshold' => $size['threshold'],
            'cat'      => $cat['parent'] . ' ~ ' . $cat['child']
           );
           $itemsizes[] = $items;
         }
        }
     }


  ?>
  <div class="col-md-8">
    
    <h3 class="text-center">Selling Treshold</h3>
    <table class="table table-bordered table-condensed table-striped">
      <thead>
        <th>Product</th>
        <th>Category</th>
        <th>Size</th>
        <th>Quantity</th>
        <th>Treshold</th>
      </thead>
      <tbody>
        <?php  foreach($itemsizes as $item):  ?>
        <tr <?= (($item['quantity'] == 0)?' class="danger"':'');?>>
          <td><?=$item['title']; ?></td>
          <td><?=$item['cat']; ?></td>
          <td><?=$item['size']; ?></td>
          <td><?=$item['quantity']; ?></td>
          <td><?=$item['threshold']; ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

 </div>
       
<?php 
   include 'includes/bfooter.php';
 
ob_end_flush();
