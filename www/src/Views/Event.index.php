<!DOCTYPE html>
<html lang="en">

<head>
  <title>Events Page </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
<?php  $total=0; ?>
  <div class="container">
    <h1>Event Page </h1>
    <div class="container">

      <div class="container">
        <h2>Filterable </h2>

        <div class="row">

          <form class="form-inline" action="/">
            <div class="form-group">
              <label for="email">Empolyee Name :</label>
              <input type="text" name="employee_name" value="<?php echo $_GET['employee_name'] ?? '' ?>" class="form-control" id="employee_name">
            </div>
            <div class="form-group">
              <label for="pwd">Event Name:</label>
              <input type="text" name="event_name" value="<?php echo $_GET['event_name'] ?? '' ?> " class="form-control" id="event_name">
            </div>
            <label for="event_date"> Date </label>
            <input id="event_date" name="event_date" class="form-control" type="date" />
            <button type="submit" class="btn btn-default">Fillter</button>
            <div class="form-group">
              <a href="/" class="btn btn-info" role="button">Reset Fillters</a>
            </div>
          </form>

        </div>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Event Name</th>
            <th>date</th>
            <th>fee</th>
            <th>Empolyee Name</th>
          </tr>
        </thead>
        <tbody>

          <?php if (!empty($this->prams['events'])):  ?>
            <?php foreach ($this->prams['events'] as $event):  ?>

              <tr>
                <td><?php echo $event['id'] ?></td>
                <td><?php echo $event['event_name'] ?></td>
                <td><?php echo $event['event_date'] ?></td>
                <td><?php echo $event['event_fee'] ?></td>
                <td><?php echo $event['employee_name'] ?></td>
              </tr>
              <?php  $total= $total+$event['event_fee']; ?>

            <?php endforeach;  ?>


        </tbody>
        <tfoot>
          <tr>
            <td colspan="3">Total Price:</td>
            <td class="danger"> <?php echo $total ?></td>
          </tr>
        </tfoot>
      <?php else: ?>
        <h2>Sorry ! Not resulets found </h2>
      <?php endif; ?>
      </table>
    </div>
  </div>

</body>

</html>