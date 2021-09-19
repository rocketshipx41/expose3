<?php

class ExIssue
{
    public $issue_no = 0;
    public $description = '';
    public $pub_date = '';
    public $pages = 0;
    public $blurb = '';

    // plus...
    public $contents = array();

    public function clear_contents()
    {
        $this->contents = array();
    }
}