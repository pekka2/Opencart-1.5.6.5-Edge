<?php
class ControllerModuleChangeModuleCss extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('module/change_module_css');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('change', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
		}


		$this->document->addStyle('view/javascript/bootstrap/opencart/opencart.css');
		$this->document->addStyle('view/javascript/font-awesome/css/font-awesome.min.css');

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_bestseller'] = $this->language->get('text_bestseller');
		$this->data['text_featured'] = $this->language->get('text_featured');
		$this->data['text_latest'] = $this->language->get('text_latest');
		$this->data['text_special'] = $this->language->get('text_special');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_description_status'] = $this->language->get('entry_description_status');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_strlen'] = $this->language->get('entry_strlen');
		$this->data['entry_product_name'] = $this->language->get('entry_product_name');
		$this->data['entry_lg'] = $this->language->get('entry_lg');
		$this->data['entry_tax_status'] = $this->language->get('entry_tax_status');
		$this->data['entry_md'] = $this->language->get('entry_md');
		$this->data['entry_caption_height'] = $this->language->get('entry_caption_height');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
	                      $modules = $this->model_setting_setting->getSetting('change');
                                             if( isset( $modules['change_module_css'] ) ){
                                               $this->data['modules'] = $modules['change_module_css'];
                                              } else {
                                                                     $this->data['modules'] = array();
                                            }

	                      if($this->config->get('change_module_css_strlen') ){
                                                                    $this->data['change_module_css_strlen'] = $this->config->get('change_module_css_strlen');
                                             }else{
                                                                    $this->data['change_module_css_strlen'] = '';
                                             }
	                      if($this->config->get('change_module_css_productname') ){
                                                                    $this->data['change_module_css_productname'] = $this->config->get('change_module_css_productname');
                                             }else{
                                                                    $this->data['change_module_css_productname'] = '';
                                             }
	                      if($this->config->get('change_module_css_tax_status') ){
                                                                    $this->data['change_module_css_tax_status'] = $this->config->get('change_module_css_tax_status');
                                             }else{
                                                                    $this->data['change_module_css_tax_status'] = '';
                                             }
	                      if($this->config->get('change_module_css_desc_status') ){
                                                                    $this->data['change_module_css_desc_status'] = $this->config->get('change_module_css_desc_status');
                                             }else{
                                                                    $this->data['change_module_css_desc_status'] = '';
                                             }
                                    
                                       $this->data['cssy'] = array('value'=>array('sy-005','sy-006','sy-007','sy-008','sy-009','sy-01','sy-02','sy-03','sy-04','sy-05','sy-06','sy-07','sy-08','sy-09','sy-1','sy-2','sy-3','sy-4','sy-5','sy-6','sy-7','sy-8','sy-9','sy-10','sy-11','sy-12'),
                                                                    'info'=>array('height:20px', 'height:40px', 'height:60px', 'height:80px','height:100px','height:120px', 'height:140px', 'height:160px', 'height:180px','height:200px','height:220px','height:240px','height:260px','height:280px','height:300px','height:320px','height:340px','height:360px','height:380px','height:400px','height:420px','height:440px','height:460px','height:480px','height:500px','height:520px')
                                                                    );   
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/account', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['action'] = $this->url->link('module/change_module_css', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['change_module_css_status'])) {
			$this->data['change_module_css_status'] = $this->request->post['change_module_css_status'];
		} else {
			$this->data['change_module_css_status'] = $this->config->get('change_module_css_status');
		}
		
		$this->template = 'module/change_module_css.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'module/change_module_css')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}