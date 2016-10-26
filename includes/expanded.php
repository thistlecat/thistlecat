
<?php if(isset($_GET['view']) and ($_GET['view'] == 'compressed') ) : ?>

<a href="<?php echo $_SERVER['PHP_SELF'] . "?lcclass=" . $_GET['lcclass']; ?>" class="btn btn-primary btn active" role="button" aria-pressed="true" title="Only display dates that have corresponding items">Turn Compressed View Off</a>

<?php else : ?>

<a href="<?php echo $_SERVER['REQUEST_URI']; ?>&view=compressed" class="btn btn-secondary btn active" role="button" aria-pressed="true" title="Display all dates (more accurate picture of collection, but can be slower to load and difficult to view if dealing with a large range of dates)">Turn Compressed View On</a>


<?php endif; ?>