<div class="row">
    <div class="col-xs-12">
        <ul class="list-group">
            <li class="list-group-item active">Tasks</li>
            <?php foreach ($tasks as $task) {
                ?>
                <li class="list-group-item" style="overflow: hidden;">
                    <form action="/task/remove/<?php echo $task['id']; ?>" method="post" class="pull-right" onclick="return confirm('Really remove task and all logged time?');">
                        <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span></button>
                    </form>
                    <form action="/task/edit/<?php echo $task['id']; ?>" method="get" class="pull-right">
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button>
                    </form>
                    <div><a href="<?php echo $task['url']; ?>"><?php echo $task['title']; ?></a> <?php echo $task['description']; ?></div>
                    <div><?php echo gmdate("H:i:s", $task['spent']); ?></div>
                </li>
            <?php } ?>
        </ul>
        <ul class="list-group">
            <li class="list-group-item active">Add new task</li>
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
</div>