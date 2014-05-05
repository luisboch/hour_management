<?php

/**
 * Description of Pagination
 *
 * @author luis
 * @since Jan 9, 2014
 */
class Pagination {

    private $amountRegisters = NULL;
    private $amountPerPage;
    private $currentPage;
    private $pageNumber;
    private $amountLinkShow = 9; #quantidade de paginas que vão aparecer na seleção de paginas da pesquisa;
    private $first;
    private $prev;
    private $next;
    private $last;
    private $targetUrl;
    private $showing;
    private $html = NULL;
    private $pageParamName = NULL;
    private $queryString;

    public function __construct() {
        
    }

    /**
     * @param int $page
     * @return string
     */
    private function getUrl($page) {
        if ($this->pageParamName === null) {
            return $this->targetUrl . '/' . $page . '?' . $this->queryString;
        } else {
            return $this->targetUrl . '?' . $this->pageParamName . '=' . $page . '&' . $this->queryString;
        }
    }

    public function calculate() {

        if ($this->amountRegisters === NULL) {
            throw new Exception("Total de registros não preenchido");
        } else if ($this->currentPage == NULL) {
            throw new Exception("Pagina atual não setada!");
        } else {

            $this->first = 1;
            $this->last = $this->first + ($this->amountLinkShow - 1);

            $medium = ceil($this->last / 2);


            $this->pageNumber = ceil($this->amountRegisters / $this->amountPerPage);


            if ($this->currentPage > $this->pageNumber - $medium) {

                $this->first = $this->pageNumber - $this->amountLinkShow;
                $this->last = $this->pageNumber;
            } else if ($this->currentPage > $medium) {

                $this->first = $this->currentPage - ($medium - 1);
                $this->last = $this->currentPage + ($medium - 1);
            }

            if ($this->last >= $this->pageNumber) {
                $this->last = $this->pageNumber;
                $this->first = $this->pageNumber - $this->amountLinkShow + 1;
            }

            if ($this->first <= 0) {
                $this->first = 1;
            }

            if ($this->last == 0) {
                $this->last = 1;
            }

            if ($this->currentPage != 1) {
                $this->prev = $this->currentPage - 1;
            } else {
                $this->prev = $this->currentPage;
            }

            if ($this->currentPage != $this->pageNumber) {
                $this->next = $this->currentPage + 1;
            } else {
                $this->next = $this->currentPage;
            }
        }
    }

    public function getPageParamName() {
        return $this->pageParamName;
    }

    public function setPageParamName($pageParamName) {
        $this->pageParamName = $pageParamName;
    }

    public function getHtml() {
        return $this->html === NULL ? $this->html = $this->generate() : $this->html;
    }

    private function generate() {
        if ($this->targetUrl == NULL) {
            throw new Exception("Url de destino não setado!");
        } else {
            $this->calculate();

            if ($this->amountRegisters > $this->amountPerPage) {
                $html = '<div class="text-right">
                            <ul class="pagination">';
                //<div>Paginas:<span class="desc">Exibindo <span>' . $this->showing . '</span> de <span>' . $this->amountRegisters . '</span> Resultados</span><div>';
                $extraClass = '';
                if ($this->currentPage == 1) {
                    $extraClass = 'disabled';
                }
                $html .= '<li class="' . $extraClass . '"><a href="' . $this->getUrl(1) . '" >&laquo;</a></li>';

                for ($i = $this->first; $i <= $this->last; $i++) {

                    $html .= '<li class="' . ($i == $this->currentPage ? 'active' : '') . '"><a href="' . $this->getUrl($i) . '">' . $i . '</a></li>';
                }

                $extraClass = '';

                if ($this->currentPage >= $this->pageNumber) {
                    $extraClass = 'disabled';
                }

                $html .= '<li class="' . $extraClass . '"><a href="' . $this->getUrl($this->pageNumber) . '">&raquo;</a></li>';
                
                $html .= '</ul></div>';
                return $html;
            }
            return '';
        }
    }

    public function getAmountRegisters() {
        return $this->amountRegisters;
    }

    public function setAmountRegisters($amountRegisters) {
        $this->amountRegisters = $amountRegisters;
    }

    public function getAmountPerPage() {
        return $this->amountPerPage;
    }

    public function setAmountPerPage($amountPerPage) {
        $this->amountPerPage = $amountPerPage;
    }

    public function getCurrentPage() {
        return $this->currentPage;
    }

    public function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function setPageNumber($pageNumber) {
        $this->pageNumber = $pageNumber;
    }

    public function getAmountLinkShow() {
        return $this->amountLinkShow;
    }

    public function setAmountLinkShow($amountLinkShow) {
        $this->amountLinkShow = $amountLinkShow;
    }

    public function getFirst() {
        return $this->first;
    }

    public function setFirst($first) {
        $this->first = $first;
    }

    public function getPrev() {
        return $this->prev;
    }

    public function setPrev($prev) {
        $this->prev = $prev;
    }

    public function getNext() {
        return $this->next;
    }

    public function setNext($next) {
        $this->next = $next;
    }

    public function getLast() {
        return $this->last;
    }

    public function setLast($last) {
        $this->last = $last;
    }

    public function getTargetUrl() {
        return $this->targetUrl;
    }

    public function setTargetUrl($targetUrl) {
        $this->targetUrl = $targetUrl;
    }

    public static function getOffset($page, $amoutPerPage) {
        return ($page * $amoutPerPage) - $amoutPerPage;
    }

    public function getShowing() {
        return $this->showing;
    }

    public function setShowing($showing) {
        $this->showing = $showing;
    }

    public function __toString() {
        return $this->getHtml();
    }

    public function getQueryString() {
        return $this->queryString;
    }

    public function setQueryString($queryString) {
        $this->queryString = $queryString;
    }

}
