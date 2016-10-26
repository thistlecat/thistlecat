<? global $thisregion; ?>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <img style="float:left;" src="milkwhite.png" width="50"><a class="navbar-brand" href="index.php">ThistleCAT <?php echo $libraryname; ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        
          <ul class="nav navbar-nav navbar-left">
          <a class="navbar-brand" href="authorsindex.php"><i class="fa fa-caret-right" aria-hidden="true"></i> Literature Module</a>
           <li><a href="authors_region.php?region=<?php echo $thisregion; ?>"><i class="fa fa-caret-right" aria-hidden="true"></i> Region Overview</a></li>

                      <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Select Layer... <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="authors_issues.php?author=<?php echo $_GET['author']; ?>">Total Checkouts</a></li>
                <li><a href="authors_lastborrowed.php?author=<?php echo $_GET['author']; ?>">Last Checkout Date</a></li>
                                <li><a href="authors_lang.php?author=<?php echo $_GET['author']; ?>">Language</a></li>
                              <li><a href="authors_primary.php?author=<?php echo $_GET['author']; ?>">Primary vs. Secondary</a></li>

                <li role="separator" class="divider"></li>
               <!-- <li class="dropdown-header">Nav header</li> -->
                <li><a href="authors.php?author=<?php echo $_GET['author']; ?>">Clear layers</a></li>
              </ul>

        
          </ul>
        
        </div>
      </div>
    </nav>