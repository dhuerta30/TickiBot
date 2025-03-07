<div class="row pdocrud-filters-container" data-objkey="<?php echo $objKey; ?>" >
    <div class="col-md-12">
        <?php if (isset($filters) && count($filters)) { ?>
            <div class="pdocrud-filters-options text-center">
                <div class="pdocrud-filter-selected">
                    <span class="pdocrud-filter-option-remove btn btn-warning" data-action="clear_all"><i class="fa fa-paint-brush"></i> <?php echo $lang["clear_all"] ?></span>
                    <a href="javascript:;" class="btn btn-primary" id="filter-button"><?php echo $lang["filter_text"] ?></a>
                </div>
                <br>
                <?php
                foreach ($filters as $filter) {
                    echo $filter;
                }
                ?>

            </div>
            <?php
        }
        ?>
    </div>
    <div class="col-md-12">
        <?php echo $data ?>
    </div>
</div>