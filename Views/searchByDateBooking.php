<?php
namespace Views;
?>
<form class="row g-3" action="<?php echo '/FinalProject4/' ?>Booking/bookingBuild" method="post">
  <div class="col-auto">
    <label for="" class="visually-hidden">From</label>
    <input type="date" value="01/01/2022" name="value1" class="form-control-plaintext" >
  </div>
  <div class="col-auto">
    <label for="" class="visually-hidden">To</label>
    <input type="date" value ="01/01/2022" name="value2" class="form-control" >
  </div>
  <div class="col-auto">
    <button type="submit" class="btn btn-primary mb-3">Confirm new date</button>
  </div>
</form>