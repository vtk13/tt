<div class="row">
    <div class="col-xs-6">
        <ul class="list-group task-list">
            <li class="list-group-item active">Tasks</li>
            <?php foreach ($tasks as $task) {
                $current = $task['id'] == $currentActivity['task_id'];
                $selected = $task['id'] == $selectedTask;
                ?>
                <li class="list-group-item <?php echo $current ? 'bg-success' : '';  ?>">
                    <form action="/track/task/<?php echo $task['id']; ?>" method="get" class="pull-right">
                        <button type="submit" class="btn btn-success"><span class="glyphicon <?php echo $selected ? 'glyphicon-arrow-right' : 'glyphicon-minus' ?>"></span></button>
                    </form>
                    <form action="/task/remove/<?php echo $task['id']; ?>" method="post" class="pull-right" onclick="return confirm('Really remove Task?');">
                        <button title="Remove task and all activity logs" type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                    </form>
                    <?php if ($current) { ?>
                        <form action="/track/stop/" method="post" class="pull-right">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-stop"></span></button>
                        </form>
                    <?php } ?>
                    <div><a href="<?php echo $task['url']; ?>"><?php echo $task['title']; ?></a> <?php echo $task['description']; ?></div>
                    <div><?php echo gmdate("H:i:s", $task['spent']) ?></div>
                </li>
            <?php } ?>
            <li class="list-group-item">
                <form action="/task/edit" method="post">
                    <div class="form-group">
                        <input class="form-control" name="title" placeholder="Title" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="description" placeholder="Description" required>
                    </div>
                    <div class="form-group">
                        <input class="form-control" name="url" placeholder="URL" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success">Add</button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
    <div class="col-xs-6">
        <ul class="list-group">
            <li class="list-group-item active">Activities</li>
            <?php if ($selectedTask) { ?>
                <?php foreach ($activities as $activity) {
                    $active = $activity['id'] == $currentActivity['activity_id'] && $activity['task_id'] == $currentActivity['task_id'];
                    ?>
                    <li class="list-group-item <?php echo $active ? 'bg-success' : '';  ?>">
                        <?php if ($active) { ?>
                            <form action="/track/stop/" method="post" class="pull-right">
                                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-stop"></span></button>
                            </form>
                        <?php } else { ?>
                            <form action="/track/start/<?php echo $selectedTask; ?>/<?php echo $activity['id']; ?>" method="post" class="pull-right">
                                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-play"></span></button>
                            </form>
                        <?php } ?>
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
            <?php } else { ?>
                <li class="list-group-item">
                    No task is selected
                </li>
            <?php } ?>
        </ul>
    </div>
</div>