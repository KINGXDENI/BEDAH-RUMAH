<?php
class SAW
{
    public $rel_alternatif;
    public $atribut;
    public $bobot;

    public $bobot_normal;
    public $minmax;
    public $normal;
    public $terbobot;
    public $total;

    function __construct($rel_alternatif, $atribut, $bobot)
    {
        $this->rel_alternatif = $rel_alternatif;
        $this->atribut = $atribut;
        $this->bobot = $bobot;

        $this->bobot_normal();
        $this->normal();
        $this->terbobot();
        $this->total();
    }
    function total()
    {
        foreach ($this->terbobot as $key => $val) {
            $this->total[$key] = array_sum($val);
        }
    }
    function terbobot()
    {
        foreach ($this->normal as $key => $val) {
            foreach ($val as $k => $v) {
                $this->terbobot[$key][$k] = $v * $this->bobot_normal[$k];
            }
        }
    }
    function normal()
    {
        $arr = array();
        foreach ($this->rel_alternatif as $key => $val) {
            $temp = array();
            foreach ($val as $k => $v) {
                $arr[$k][$key] = $v;
            }
        }
        $this->minmax = array();
        foreach ($arr as $key => $val) {
            $this->minmax[$key]['min'] = min($val);
            $this->minmax[$key]['max'] = max($val);
        }
        $this->normal = array();
        foreach ($this->rel_alternatif as $key => $val) {
            foreach ($val as $k => $v) {
                if (strtolower($this->atribut[$k]) == 'benefit')
                    $this->normal[$key][$k] = $v / $this->minmax[$k]['max'];
                else
                    $this->normal[$key][$k] = $this->minmax[$k]['min'] / $v;
            }
        }
    }
    function bobot_normal()
    {
        $total = array_sum($this->bobot);
        foreach ($this->bobot as $key => $val) {
            $this->bobot_normal[$key] = $val / $total;
        }
    }
}
