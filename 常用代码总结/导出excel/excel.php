 <?php
class IndexController extends Controller {
 //把一页一页的商品信息导出到excel里边
    public function excel_out_page(){
        //通过excel导出当前页的商品信息
        //设置当前页码
        $where = '2>1';
        $nowpage = !empty($_GET['page'])?$_GET['page']:1;//当前页码
        $per = 10;
        if(session('utype') == 2){
         $where .= " AND `uid` =".session('uid');
        }
        //根据页码获得数据信息出来
        $list = M('qiye')->alias('qiye')->join('tp_hetong on tp_hetong.qid=qiye.id')->where($where)->limit(($nowpage-1)*$per,$per)->order('qiye.id desc')->select();

        //设置Excel
        require_once './Common/Plugin/Excel/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
                             ->setLastModifiedBy("Maarten Balliauw")
                             ->setTitle("PHPExcel Test Document")
                             ->setSubject("PHPExcel Test Document")
                             ->setDescription("Test document for PHPExcel, generated using PHP classes.")
                             ->setKeywords("office PHPExcel php")
                             ->setCategory("Test result file");

        //设置标题
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '记录id')
            ->setCellValue('B1', '合同编号')
            ->setCellValue('C1', '申请日期')
            ->setCellValue('D1', '合同金额')
            ->setCellValue('E1', '实收金额')
            ->setCellValue('F1', '申请人')
            ->setCellValue('G1', '申请版本')
            ->setCellValue('H1', '开通年限')
            ->setCellValue('I1', '代运营周期')
            ->setCellValue('J1', '所属部门')
            ->setCellValue('K1', '部门经理')
            ->setCellValue('L1', '客户来源')
            ->setCellValue('M1', '企业名称')
            ->setCellValue('N1', '联系地址')
            ->setCellValue('O1', '法人')
            ->setCellValue('P1', '法人手机号')
            ->setCellValue('Q1', '运营人')
            ->setCellValue('R1', '运营人手机号')
            ->setCellValue('S1', '微盟用户名')
            ->setCellValue('T1', '密码')
            ->setCellValue('U1', 'QQ')
            ->setCellValue('V1', '邮箱')
            ->setCellValue('W1', '微信公众号')
            ->setCellValue('X1', '公众号密码')
            ->setCellValue('Y1', '开通状态')
            ->setCellValue('Z1', '开通类别')
            ->setCellValue('AA1', '客户特殊要求');
        //设置具体内容
        foreach($list as $k => $v){
            $i = $k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$i, $v['id'])
                ->setCellValue('B'.$i, $v['bianhao'])
                ->setCellValue('C'.$i, $v['date'])
                ->setCellValue('D'.$i, $v['money'])
                ->setCellValue('E'.$i, $v['realmoney'])
                ->setCellValue('F'.$i, $v['shengqingren'])
                ->setCellValue('G'.$i, $v['banben'])
                ->setCellValue('H'.$i, $v['nianxian'])
                ->setCellValue('I'.$i, $v['daiyunyingdate'])
                ->setCellValue('J'.$i, $v['dept'])
                ->setCellValue('K'.$i, $v['jingli'])
                ->setCellValue('L'.$i, $v['laiyuan'])
                ->setCellValue('M'.$i, $v['qname'])
                ->setCellValue('N'.$i, $v['qaddr'])
                ->setCellValue('O'.$i, $v['faren'])
                ->setCellValue('P'.$i, $v['fatentel'])
                ->setCellValue('Q'.$i, $v['yunying'])
                ->setCellValue('R'.$i, $v['yunyingtel'])
                ->setCellValue('S'.$i, $v['weimeng'])
                ->setCellValue('T'.$i, $v['weimengpwd'])
                ->setCellValue('U'.$i, $v['qq'])
                ->setCellValue('V'.$i, $v['email'])
                ->setCellValue('W'.$i, $v['wechat'])
                ->setCellValue('X'.$i, $v['wechatpwd'])
                ->setCellValue('Y'.$i, $v['status'])
                ->setCellValue('Z'.$i, $v['leibie'])
                ->setCellValue('AA'.$i, $v['detail']);
        }
      $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
      //设置http协议，下载支持
      header("Content-Type:application/force-download");
      //设置下载的内容是excel
      header("Content-Type:application/vnd.ms-execl");
      //把下载的内容设置为8机制流内容
      header("Content-Type:application/octet-stream");
      //设置http协议，下载支持
      header("Content-Type:application/download");
      //下载excel名字的定义
      header('Content-Disposition:attachment;filename="商品信息.xlsx"');
      //内容设置为二进制形式传输
      header("Content-Transfer-Encoding:binary");
      //把excel文件直接提供为下载形式
      $objWriter->save('php://output');
    }
}