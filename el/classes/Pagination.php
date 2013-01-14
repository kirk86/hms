<?php

class Pagination
{
    public $items_per_page;
    public $total_items;
    public $current_page;
    public $num_pages;
    public $mid_range;
    public $low;
    public $high;
    public $limit;
    public $return;
    public $default_ipp = 5;
    public $querystring;
    public $option;

    public function __construct()
    {
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp'] : $this->
            default_ipp;
    }

    public function paginate()
    {
        if (isset($_GET['ipp']) && $_GET['ipp'] == 'All')
        {
            $this->num_pages = ceil($this->total_items / $this->default_ipp);
            $this->items_per_page = $this->default_ipp;
        }
        else
        {
            if (!is_numeric($this->items_per_page) || $this->items_per_page <= 0)
                $this->items_per_page = $this->default_ipp;
            $this->num_pages = ceil($this->total_items / $this->items_per_page);
        }
        $this->current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // must be numeric > 0

        if ($this->current_page < 1 || !is_numeric($this->current_page))
            $this->current_page = 1;

        if ($this->current_page > $this->num_pages)
            $this->current_page = $this->num_pages;

        $prev_page = $this->current_page - 1;
        $next_page = $this->current_page + 1;

        if ($_GET)
        {
            $args = explode("&", $_SERVER['QUERY_STRING']);
            foreach ($args as $arg)
            {
                $keyval = explode("=", $arg);
                if ($keyval[0] != "page" && $keyval[0] != "ipp")
                    $this->querystring .= "&" . $arg;
            }
        }

        if ($_POST)
        {
            foreach ($_POST as $key => $val)
            {
                if ($key != "page" && $key != "ipp")
                    $this->querystring .= "&$key=$val";
            }
        }

        if ($this->num_pages > 10)
        {
            $this->return = ($this->current_page != 1 && $this->total_items >= 10) ?
                "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page$this->querystring\">&laquo; Προηγούμενη</a> " : "<span class=\"inactive\" href=\"#\">&laquo; Προηγούμενη</span> ";

            $this->start_range = $this->current_page - floor($this->mid_range / 2);
            $this->end_range = $this->current_page + floor($this->mid_range / 2);

            if ($this->start_range <= 0)
            {
                $this->end_range += abs($this->start_range) + 1;
                $this->start_range = 1;
            }
            if ($this->end_range > $this->num_pages)
            {
                $this->start_range -= $this->end_range - $this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range, $this->end_range);

            for ($i = 1; $i <= $this->num_pages; $i++)
            {
                if ($this->range[0] > 2 && $i == $this->range[0])
                    $this->return .= " ... ";
                // loop through all pages. if first, last, or in range, display
                if ($i == 1 || $i == $this->num_pages || in_array($i, $this->range))
                {
                    $this->return .= ($i == $this->current_page && isset($_GET['page']) && $_GET['page'] != 'All') 
                    ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> " :
                       "<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
                }
                if ($this->range[$this->mid_range - 1] < $this->num_pages - 1 && $i == $this->
                    range[$this->mid_range - 1])
                    $this->return .= " ... ";
            }
            $this->return .= (($this->current_page != $this->num_pages && $this->
                total_items >= 10) && (isset($_GET['page']) && $_GET['page'] != 'All')) ?
                "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page$this->querystring\">Επόμενη &raquo;</a>\n" : "<span class=\"inactive\" href=\"#\">&raquo; Επόμενη</span>\n";
                
            $this->return .= (isset($_GET['page']) && $_GET['page'] == 'All') ? 
            "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">Όλα</a> \n" :
            "<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">Όλα</a> \n";
        }
        else
        {
            for ($i = 1; $i <= $this->num_pages; $i++)
            {
                $this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> " :
                    "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
            }
            $this->return .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">Όλα</a> \n";
        }
        $this->low = ($this->current_page - 1) * $this->items_per_page;
        $this->high = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? $this->total_items : 
        ($this->current_page * $this->items_per_page) - 1;
        $this->limit = (isset($_GET['ipp']) && $_GET['ipp'] == 'All') ? "" : " LIMIT $this->low,$this->items_per_page";
    }

    public function displayItemsPerPage()
    {
        $items = '';
        $ipp_array = array(5, 10, 25, 50, 100, 'All');
        foreach ($ipp_array as $ipp_opt)
            $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n" :
                "<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        return "<span class=\"paginate\">Στοιχεία ανά σελίδα:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
    }

    public function displayJumpMenu()
    {
        for ($i = 1; $i <= $this->num_pages; $i++)
        {
            $this->option .= ($i == $this->current_page) ? "<option value=\"$i\" selected>$i</option>\n" :
                "<option value=\"$i\">$i</option>\n";
        }
        return "<span class=\"paginate\">Σελίδα:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$this->option</select>\n";
    }

    public function displayPages()
    {
        return $this->return;
    }
}
