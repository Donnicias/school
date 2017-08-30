<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Data_books_statistics_m extends MY_Model {
    function __construct() {
        parent::__construct();
    }


    function get_order_by_book_overal_analysis() {
        $this->db->select('book, (SELECT count(*) from book where status=0) as available,(select count(*) from book where status=1) as borrowed,(select count(*) from book where status=99) as lost,(select count(*) from book) as registered')->from("book")->group_by("book")->order_by("subject_code asc");
        $query=$this->db->get();
        return $query->result();
    }

    function get_lost_books_per_day() {
        $this->db->select('lost_date as ldate,count(*) as total')->from("lost_books_history")->where("found_status = 0")->where("lost_date IS NOT NULL")->group_by("lost_date")->order_by("lost_date desc");
        $query=$this->db->get();
        return $query->result();
    }

    function get_total_not_found_books() {
        $this->db->select('count(*) as total_not_found')->from(" lost_books_history")->where("found_status = 0");
        $query=$this->db->get();
        return $query->row();
    }

    function get_total_lost_books() {
        $this->db->select('count(*) as total_lost')->from(" lost_books_history")->where("lost_date IS NOT NULL");
        $query=$this->db->get();
        return $query->row();
    }

    function get_lost_and_found_books_per_day() {
        $this->db->select('found_date as ldate,count(*) as total_found,')->from(" lost_books_history")->where("found_status = 1")->where("lost_date IS NOT NULL")->group_by("found_date")->order_by("found_date desc");
        $query=$this->db->get();
        return $query->result();
    }

    function get__total_lost_and_found_books() {
        $this->db->select('count(*) as total_found,')->from("lost_books_history")->where("found_status = 1")->where("lost_date IS NOT NULL");
        $query=$this->db->get();
        return $query->row();
    }

    function get_order_by_book_statistics($book) {
        $this->db->select('(SELECT count(*) from book where status=0 AND book="'.$book.'") as available,(select count(*) from book where status=1 AND book="'.$book.'") as borrowed,(select count(*) from book where status=99 AND book="'.$book.'") as lost,(select count(*) from book where book="'.$book.'") as registered')->from("book");
        $query=$this->db->get();
        return $query->row();
    }
}
?>