<?php
/**
 * Modern Bootstrap 4 Responsive Pagination Helper
 */

if (!function_exists('renderPagination')) {
    function renderPagination($total_records, $per_page = 10, $page = 1, $url = '?') {
        $per_page = max(1, (int)$per_page);
        $total_records = max(0, (int)$total_records);
        $total_pages = (int)ceil($total_records / $per_page);
        
        if ($total_pages <= 1) {
            return '';
        }
        
        $page = max(1, min($total_pages, (int)$page));
        
        // Build base URL for page parameter
        if ($url === '?') {
            // Default: use current page path + ?page=
            $page_url = '?page=';
        } elseif (strpos($url, 'page=') !== false) {
            // Already has page= in URL, replace the number
            $page_url = preg_replace('/page=\d*/', 'page=', $url);
        } elseif (strpos($url, '?') !== false) {
            // Has query string but no page param — append &page=
            $page_url = rtrim($url, '&') . '&page=';
        } else {
            // Plain path — add ?page=
            $page_url = rtrim($url, '/') . '?page=';
        }
        
        $adjacents = 2;
        $out = '<nav aria-label="Page navigation" class="mt-4 mb-3">';
        $out .= '<ul class="pagination justify-content-center mb-2">';
        
        // First & Previous Buttons
        if ($page > 1) {
            $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . '1" title="First Page">&laquo;&laquo; First</a></li>';
            $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . ($page - 1) . '" title="Previous Page">&laquo; Prev</a></li>';
        } else {
            $out .= '<li class="page-item disabled"><span class="page-link">&laquo;&laquo; First</span></li>';
            $out .= '<li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>';
        }
        
        // Windowed Page Numbers
        $start = max(1, $page - $adjacents);
        $end = min($total_pages, $page + $adjacents);
        
        if ($start > 1) {
            $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . '1">1</a></li>';
            if ($start > 2) {
                $out .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $page) {
                $out .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . $i . '">' . $i . '</a></li>';
            }
        }
        
        if ($end < $total_pages) {
            if ($end < $total_pages - 1) {
                $out .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . $total_pages . '">' . $total_pages . '</a></li>';
        }
        
        // Next & Last Buttons
        if ($page < $total_pages) {
            $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . ($page + 1) . '" title="Next Page">Next &raquo;</a></li>';
            $out .= '<li class="page-item"><a class="page-link" href="' . $page_url . $total_pages . '" title="Last Page">Last &raquo;&raquo;</a></li>';
        } else {
            $out .= '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
            $out .= '<li class="page-item disabled"><span class="page-link">Last &raquo;&raquo;</span></li>';
        }
        
        $out .= '</ul>';
        
        $start_item = (($page - 1) * $per_page) + 1;
        $end_item = min($total_records, $page * $per_page);
        
        // Items per page selector
        $current_url = htmlspecialchars($_SERVER['PHP_SELF']);
        $out .= '<div class="d-flex justify-content-center align-items-center mt-3 flex-wrap">';
        $out .= '<div class="text-muted small mr-3">Showing ' . number_format($start_item) . ' - ' . number_format($end_item) . ' of ' . number_format($total_records) . ' records (Page ' . $page . ' of ' . $total_pages . ')</div>';
        
        $out .= '<div class="form-inline ml-3">';
        $out .= '<label class="small text-muted mr-2" for="limitSelect">Items per page:</label>';
        $out .= '<select id="limitSelect" class="form-control form-control-sm rounded-pill" onchange="window.location.href=\''.$current_url.'?limit=\'+this.value">';
        $limits = [10, 25, 50, 100];
        foreach($limits as $l) {
            $selected = ($per_page == $l) ? 'selected' : '';
            $out .= '<option value="'.$l.'" '.$selected.'>'.$l.'</option>';
        }
        $out .= '</select>';
        $out .= '</div>';
        $out .= '</div>';
        
        $out .= '</nav>';
        
        return $out;
    }
}

/**
 * Legacy compatibility wrapper for pagination()
 */
if (!function_exists('pagination')) {
    function pagination($total_or_query, $per_page = 10, $page = 1, $url = '?') {
        if (is_numeric($total_or_query)) {
            return renderPagination((int)$total_or_query, $per_page, $page, $url);
        }
        global $conn;
        $count = 0;
        if ($conn) {
            $res = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM letter");
            if ($res && $r = mysqli_fetch_assoc($res)) {
                $count = (int)$r['cnt'];
            }
        }
        return renderPagination($count, $per_page, $page, $url);
    }
}
?>