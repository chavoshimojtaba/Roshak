<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class report  extends Controller
{

    protected $block = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->load->model('model_category');
        $res = $this->model_category->get_list('product');
        if ($res->count > 0) {
            require_once(DIR_HELPER . "helper_html.php");
            $tree = buildMenuTree($res->result);
            $this->html->menuObject = json_encode($tree, true);
        }
        $this->html->set_data($data);
        $this->display();
    }public function category ()
    {
        // generateCategoryTemplatesCache();
        $filetype  = values('filetype');
        $attribute_items = [];
		foreach ($filetype as $k_6 => $v_6) {
			$attribute_items[] = ['value' => $v_6,   'key' => $k_6];
		}
        $this->html->filetype = $attribute_items;
        $this->loop[] = 'filetype';
        $this->display();
    }
    public function plan()
    {
        $this->display();
    }
    public function transaction()
    {
        $this->display();
    }


    public function excel()
    {
        $status = [
            "done" => "تکمیل سفارش",
            "pend" => "در صف",
            "failed" => "ناموفق",
            "cancel" => "لغو شده",
        ];
        $type = [
            "bank" => "خرید تکی",
            "subscription" => "اشتراکی",
        ];
        $data = json_decode(decode_html_tag($this->router->request_get(), true)['filters'], true);
        $this->load->model('model_financial');
        $res = $this->model_financial->report($data);
        // //pr($res,true);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        if ($res->count > 0) {
            $activeWorksheet->setCellValue('A1', 'نوع پرداختی');
            $activeWorksheet->setCellValue('B1', 'شماره سریال');
            $activeWorksheet->setCellValue('C1', 'طراح');
            $activeWorksheet->setCellValue('D1', 'مشتری');
            $activeWorksheet->setCellValue('E1', 'محصول');
            $activeWorksheet->setCellValue('F1', 'قیمت محصول');
            $activeWorksheet->setCellValue('G1', 'تخفیف');
            $activeWorksheet->setCellValue('H1', 'کد تخفیف');
            $activeWorksheet->setCellValue('I1', 'تاریخ');
            $activeWorksheet->setCellValue('J1', 'وضعیت');
            $activeWorksheet->setCellValue('K1', 'قیمت کل');

            $row = 2;
            // ksort($result);
            foreach ($res->result  as $val) {
                $val['total_price'] = toman($val['total_price'], true);
                $activeWorksheet->setCellValue('A' . $row, $type[$val["type"]]);
                $activeWorksheet->setCellValue('B' . $row, $val["serial"]);
                $activeWorksheet->setCellValue('C' . $row, $val["designer"]);
                $activeWorksheet->setCellValue('D' . $row, $val["member"]);
                $activeWorksheet->setCellValue('E' . $row, $val["product_title"]);
                $activeWorksheet->setCellValue('F' . $row, $val["product_price"]);
                $activeWorksheet->setCellValue('G' . $row, $val["discount"]);
                $activeWorksheet->setCellValue('H' . $row, $val["discount_code"]);
                $activeWorksheet->setCellValue('I' . $row, g2pt($val["createAt"]));
                $activeWorksheet->setCellValue('J' . $row, $status[$val["status"]]);
                $activeWorksheet->setCellValue('K' . $row, $val["total_price"]);
                // //pr($val);
                $row++;
            }
        }
        // //pr($result,true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '4CAF50',
                ],
                'endColor' => [
                    'argb' => '4CAF50',
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('I1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('J1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('K1')->applyFromArray($styleArray);

        // //pr(date("D M d, Y G:i"),true);
        $fileName = strtotime('now');
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '.xlsx' . '"');

        $writer->save('php://output');
        // unlink($fileName);


    }
    public function plan_excel()
    {
        $status = [
            "ended" => "اتمام اشتراک",
            "pend" => "فعال",
        ];
        $data = json_decode(decode_html_tag($this->router->request_get(), true)['filters'], true);
        $this->load->model('model_financial');
        $res = $this->model_financial->plan_report($data);
        // //pr($res,true);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        if ($res->count > 0) {
            $activeWorksheet->setCellValue('A1', 'وضعیت');
            $activeWorksheet->setCellValue('B1', 'مشتری');
            $activeWorksheet->setCellValue('C1', 'اشتراک');
            $activeWorksheet->setCellValue('D1', 'تاریخ');
            $activeWorksheet->setCellValue('E1', 'قیمت کل');


            $row = 2;
            // ksort($result);
            foreach ($res->result  as $val) {
                $val['total_price'] = toman($val['total_price'], true);
                $activeWorksheet->setCellValue('A' . $row, $status[$val["status"]]);
                $activeWorksheet->setCellValue('B' . $row, $val["member"]);
                $activeWorksheet->setCellValue('C' . $row, $val["plan"]);
                $activeWorksheet->setCellValue('D' . $row, g2pt($val["createAt"]));
                $activeWorksheet->setCellValue('E' . $row, $val["total_price"]);
                // //pr($val);
                $row++;
            }
        }
        // //pr($result,true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '4CAF50',
                ],
                'endColor' => [
                    'argb' => '4CAF50',
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);

        // //pr(date("D M d, Y G:i"),true);
        $fileName = strtotime('now');
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '.xlsx' . '"');

        $writer->save('php://output');
        // unlink($fileName);


    }
    public function transaction_excel()
    {

        $type = [
            "product"=>"محصول",
            "subscription"=>"اشتراک",
        ];
        $status = [
            "done"=>"تکمیل  ",
            "pend"=>"در صف",
            "failed"=>"ناموفق",
            "bank"=>"بانک",
        ];
        $data = json_decode(decode_html_tag($this->router->request_get(), true)['filters'], true);
        $this->load->model('model_financial');
        $res = $this->model_financial->transaction_report($data);
        // //pr($res,true);
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();
        if ($res->count > 0) {
            $activeWorksheet->setCellValue('A1', 'وضعیت');
            $activeWorksheet->setCellValue('B1', 'مشتری');
            $activeWorksheet->setCellValue('C1', 'کد رهگیری');
            $activeWorksheet->setCellValue('D1', 'کد بانک');
            $activeWorksheet->setCellValue('E1', 'پیام بانک');
            $activeWorksheet->setCellValue('F1', 'نوع');
            $activeWorksheet->setCellValue('G1', 'تاریخ');
            $activeWorksheet->setCellValue('H1', 'مبلغ');


            $row = 2;
            // ksort($result);
            foreach ($res->result  as $val) {
                $val['total_price'] = toman($val['total_price'], true);
                $activeWorksheet->setCellValue('A' . $row, $status[$val["status"]]);
                $activeWorksheet->setCellValue('B' . $row, $val["member"]);
                $activeWorksheet->setCellValue('C' . $row, $val["tracking_code"]);
                $activeWorksheet->setCellValue('D' . $row,  $val["bank_code"] );
                $activeWorksheet->setCellValue('E' . $row, $val["bank_message"]);
                $activeWorksheet->setCellValue('F' . $row, $type[$val["type"]]);
                $activeWorksheet->setCellValue('G' . $row, g2pt($val["createAt"]));
                $activeWorksheet->setCellValue('H' . $row, $val["total_price"]);
                // //pr($val);
                $row++;
            }
        }
        // //pr($result,true);
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '4CAF50',
                ],
                'endColor' => [
                    'argb' => '4CAF50',
                ],
            ],
        ];
        $spreadsheet->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('G1')->applyFromArray($styleArray);
        $spreadsheet->getActiveSheet()->getStyle('H1')->applyFromArray($styleArray);

        // //pr(date("D M d, Y G:i"),true);
        $fileName = strtotime('now');
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . urlencode($fileName) . '.xlsx' . '"');

        $writer->save('php://output');
        // unlink($fileName);


    }
    private function display()
    {

        out([
            'content' => $this->html->tab_links(
                [],
                min_template(
                    $this->html->get_string('array'),
                    $this->loop,
                    $this->router->method
                ),
                $this->router->method
            )
        ], 'admin');
    }
}
