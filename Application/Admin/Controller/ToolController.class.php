<?php
namespace Admin\Controller;
use Think\Controller;
use PhpOffice\PhpSpreadsheet;

// spl_autoload_register(function ($class) {
//     // include 'classes/' . $class . '.class.php';
//     $file = LIB_PATH."Org/Util/".$class.".php";
//     // echo $file;
//     if(file_exists($file))
//         require_once($file);  
// },false); 
// echo LIB_PATH;
class ToolController extends Controller
{


    // /*
    // *@param filename string 文件名
    // *@param header 二维数组，表头属性，一维存放所有表头的数组，第二维存放名字、宽度、key等属性
    // *@param content 二维数组，数据维用key=>value模式，与表头保持一致
    // */
    // public function export2Excel($filename,$header,$content)
    // {
    //     set_time_limit(0);
    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $objWriter = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    //     $objActSheet = $spreadsheet->getActiveSheet();


    //     if(!is_array($header)||!is_array($content)){
    //         throw new \Exception("Wrong Parameters!", 1);
    //     }

    //     foreach ($header as $key => $value) {
    //         $alpha = strtoupper(chr($key+65));//大写字母
    //         $tableHeader = $alpha."1";
    //         $objActSheet->setCellValue($tableHeader, $value["name"]);
    //         $spreadsheet->getActiveSheet()->getColumnDimension($alpha)->setWidth($value["width"]);
    //         $spreadsheet->getActiveSheet()->getStyle($alpha)->getAlignment()->setWrapText(true); 
    //     }
    //     $spreadsheet->getDefaultStyle()->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
    //     $spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());
        

    //     $savename = date("ymdHi").$filename;
    //     header("Content-Type: application/vnd.ms-excel;charset=gbk");  
    //     header("Content-Disposition: attachment; filename=".$savename.".xlsx");  
    //     header("Pragma: no-cache");
    //     foreach($content as $i=>$row_content){
    //         $row = $i+2;
    //         foreach ($header as $key => $value) {
    //             $alpha = strtoupper(chr($key+65));//大写字母
    //             $tableHeader = $alpha.$row;
    //             $objActSheet->setCellValue($tableHeader, $row_content[$value["key"]]);
    //         }
    //     }
    //     $objWriter = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $objWriter->save('php://output'); //文件通过浏览器下载
    //     die();
    // }

    /*
    *@param filename string 文件名
    *@param header 二维数组，表头属性，一维存放所有表头的数组，第二维存放名字、宽度、key等属性
    *@param content 二维数组，数据维用key=>value模式，与表头保持一致
    */
    public function exportToExcel($filename,$header,$content)
    {
        set_time_limit(0);
        import("Org.Util.PHPExcel");
        import("Org.Util.PHPExcel.IOFactory");
        import("Org.Util.PHPExcel.Writer.Excel2007");
        $objPHPExcel = new \PHPExcel();
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objActSheet = $objPHPExcel->getActiveSheet();

        $cacheMethod = \PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;  
        $cacheSettings = array();  
        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 

        if(!is_array($header)||!is_array($content)){
            throw new \Exception("Wrong Parameters!", 1);
        }

        foreach ($header as $key => $value) {
            $alpha = strtoupper(chr($key+65));//大写字母
            $tableHeader = $alpha."1";
            $objActSheet->setCellValue($tableHeader, $value["name"]);
            $objPHPExcel->getActiveSheet()->getColumnDimension($alpha)->setWidth($value["width"]);
            $objPHPExcel->getActiveSheet()->getStyle($alpha)->getAlignment()->setWrapText(true); 
        }
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
        

        $savename = date("ymdHi").$filename;
        header("Content-Type: application/vnd.ms-excel;charset=gbk");  
        header("Content-Disposition: attachment; filename=".$savename.".xlsx");  
        header("Pragma: no-cache");
        foreach($content as $i=>$row_content){
            $row = $i+2;
            foreach ($header as $key => $value) {
                $alpha = strtoupper(chr($key+65));//大写字母
                $tableHeader = $alpha.$row;
                $objActSheet->setCellValue($tableHeader, $row_content[$value["key"]]);
            }
        }
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output'); //文件通过浏览器下载
        die();
    }

}