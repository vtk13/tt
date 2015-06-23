<div class="row">
    <div class="col-xs-12">
        <ul class="list-group">
            <li class="list-group-item active">Activities</li>
            <?php foreach ($activities as $activity) {
                ?>
                <li class="list-group-item" style="overflow: hidden;">
                    <form action="/activity/remove/<?php echo $activity['id']; ?>" method="post" class="pull-right" onclick="return confirm('Really remove activity and all logged time?');">
                        <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                    </form>
                    <form action="/activity/edit/<?php echo $activity['id']; ?>" method="get" class="pull-right">
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button>
                    </form>
                    <div><?php echo $activity['title']; ?></div>
                    <div><?php echo gmdate("H:i:s", $activity['spent']) ?></div>
                </li>
            <?php } ?>
            <li class="list-group-item">
                <form action="/activity/edit" method="post" class="form-inline">
                    <input class="form-control" name="title" placeholder="New activity type...">
                    <button class="btn btn-success">Add</button>
                </form>
            </li>
        </ul>
    </div>
</div>