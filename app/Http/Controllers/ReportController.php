<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        return view('report.index');
    }

    public function exportWord(){
        // Data
        $headers = ['STT', 'Tên sản phẩm', 'Đơn giá', 'IMEI/Số lượng', 'Thành tiền'];
        $details = [
            ['product_id' => 322, 'product_name' => 'Điện thoại xiaomi MI 5X Xanh 6-64GB Hàn Quốc', 'price' => 4600000 , 'quantity' => 'ADXFD23343', 'real_price' => 46000000],
            ['product_id' => 322, 'product_name' => 'Điện thoại xiaomi MI 5X Xanh 6-64GB Hàn Quốc', 'price' => 4600000 , 'quantity' => 'ADXFD23343', 'real_price' => 46000000],
            ['product_id' => 344, 'product_name' => 'Tai nghe Airport 2:1', 'price' => 50000, 'quantity' => 2, 'real_price' => 100000],
            ['product_id' => 344, 'product_name' => 'Tai nghe Airport 2:1', 'price' => 50000, 'quantity' => 2, 'real_price' => 100000],
            ['product_id' => 344, 'product_name' => 'Tai nghe Airport 2:1', 'price' => 50000, 'quantity' => 2, 'real_price' => 100000],
        ];
        // Xuất word
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        $section->addText('Hoá Đơn #2242' ,array('name'=>'Arial','size' => 20,'bold' => true));
        // $section->addImage("./images/Krunal.jpg");
        // Add table
        $tableStyle = array(
            'borderColor' => '006699',
            'borderSize'  => 6,
            'cellMargin'  => 50
        );
        $firstRowStyle = array('bgColor' => '66BBFF');
        $phpWord->addTableStyle('myTable', $tableStyle, $firstRowStyle);
        $table = $section->addTable('myTable');
        $table->addRow();
        foreach($headers as $header){
            $table->addCell()->addText($header);
        }
        foreach($details as $index => $detail){
            $table->addRow();
            $table->addCell()->addText($index+1);
            $table->addCell()->addText($detail['product_name'] ?? '');
            $table->addCell()->addText($detail['price'] ?? '');
            $table->addCell()->addText($detail['quantity'] ?? '');
            $table->addCell()->addText($detail['real_price'] ?? '');
        }

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Appdividend.docx');
        return response()->download(public_path('Appdividend.docx'));
    }

    public function exportWord2(){
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // $phpWord->addParagraphStyle('Heading2', array('alignment' => 'center'));
        $html = view('report._table')->render();
        // dd($html);
        $section = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);
        // return view('report._table');
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('Appdividend.docx');
        return response()->download(public_path('Appdividend.docx'));
    }
}
