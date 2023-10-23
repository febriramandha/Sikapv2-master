<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Created By: Irvan Aulia
 * 2023
 */

class Ksb_keuangan extends App_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->_init();
		$this->breadcrumbs->push('Kasubag Keuangan', 'administrator/ksb-keuangan');
		$this->data['title'] = "Kasubag Keuangan";
		$this->load->model(['m_instansi', 'm_user']);
	}

	private function _init()
	{
		$this->output->set_template('app');
		$this->load->js('public/themes/plugin/autocomplete/dist/latest/bootstrap-autocomplete.js');
	}

	public function index()
	{
		$this->data['sub_title']  = "Kasubag Keuangan";
		$this->data['breadcrumb'] = $this->breadcrumbs->show();
		$this->data['instansi']	  = $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('ksb_keuangan/v_index', $this->data);
	}

	public function indexJson()
	{
		$this->output->unset_template();
		$instansi = decrypt_url($this->input->post('instansi'), 'instansi');
		if ($instansi) {
			$level 	  = $this->db->select('level')->get_where('v_instansi_all', ['id' => $instansi])->row()->level;
		}
		$this->load->library('datatables');
		$this->datatables->select('a.id, c.nama, b.dept_name, b.dept_alias, b.path_info, c.nip, d.jabatan, c.gelar_dpn,c.gelar_blk')
			->from('pejabat_instansi a')
			->join('v_instansi_all b', 'b.id=a.dept_id')
			->join('v_users_all c', 'a.user_id=c.id')
			->join('sp_pegawai d', 'a.user_id=d.user_id')
			->where('pejabat_id', 8)
			->order_by('c.no_urut')
			->add_column('nama_nip', '$1', 'nama_icon_nip(nama,gelar_dpn,gelar_blk,nip)')
			->add_column('action', '<a href="' . base_url('administrator/ksb-keuangan/edit/') . '$1">
        							<i class="icon-pencil5 text-info-400"></i>
					                </a>
					              <span class="confirm-aksi list-icons-item text-warning-600" msg="Benar ingin hapus data ini?" title="hapus pejabat" style="cursor:pointer;" id="$1">
					              <i class="icon-bin"></i>
					              </span>', 'encrypt_url(id,"pejabatinstansi_id")');
		if ($instansi) {
			$this->datatables->where("b.path_id['" . $level . "']='" . $instansi . "'");
		}
		return $this->output->set_output($this->datatables->generate());
	}

	public function add()
	{
		$this->data['sub_title'] 	= "Tambah Data Kasubag Keuangan";
		$this->breadcrumbs->push('Tambah Kasubag Keuangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('ksb_keuangan/v_add', $this->data);
	}

	public function edit($id)
	{
		$this->data['sub_title'] 	= "Edit Data Kasubag Keuangan";
		$this->breadcrumbs->push('Edit Kasubag Keuangan', '/');
		$this->data['breadcrumb'] 	= $this->breadcrumbs->show();
		$this->data['user']			= $this->m_user->GetUserPejabat(decrypt_url($id, 'pejabatinstansi_id'), 8)->row();
		$this->data['instansi']		= $this->m_instansi->GetInstasiDeptID($this->session->userdata('tpp_dept_id'))->result();
		$this->load->view('ksb_keuangan/v_edit', $this->data);
	}

	public function AjaxGet()
	{
		$this->output->unset_template();
		$this->mod = $this->input->get('modul');
		if ($this->mod == "listPejabat") {
			$list_pegawai = $this->m_user->GetUserByEselonQry(['3b', '4a', '4b'])->result();
			$data_user = array();
			foreach ($list_pegawai as $row) {
				$sub_array['id'] = encrypt_url($row->id, 'user_id');
				$sub_array['nama'] = $row->nama;
				$sub_array['nip'] = $row->nip;
				$sub_array['jabatan'] = $row->jabatan;
				$sub_array['icon'] = base_url('uploads/avatar/thumb/' . $row->avatar);
				$data_user[] = $sub_array;
			}
			if ($list_pegawai) {
				$this->output->set_output(json_encode(['results' => $data_user]));
			} else {
				$this->output->set_output(json_encode(['status' => false, 'message' => 'Gagal mengambil data.', 'results' => []]));
			}
		}
	}

	public function AjaxSave()
	{
		$this->output->unset_template();
		$this->form_validation->set_rules('user', 'pejabat', 'required')
			->set_rules('instansi', 'instansi', 'required');
		$this->form_validation->set_error_delimiters('<div><spam class="text-danger"><i>* ', '</i></spam></div>');

		if ($this->form_validation->run() == TRUE) {
			$this->mod = $this->input->post('mod');
			if ($this->mod == "add") {
				$where = array(
					'dept_id' 	=> decrypt_url($this->input->post('instansi'), 'instansi'),
					'pejabat_id' => 8
				);
				$cek =  $this->db->get_where('pejabat_instansi', $where)->row();

				if (!$cek) {
					$data = array(
						'user_id' 	=> decrypt_url($this->input->post('user'), 'user_id'),
						'dept_id' 	=> decrypt_url($this->input->post('instansi'), 'instansi'),
						'pejabat_id' 	=> 8,
						'created_at' 	=> date('Y-m-d H:i:s'),
						'created_by'	=> $this->session->userdata('tpp_user_id')
					);
					$this->return = $this->db->insert('pejabat_instansi', $data);
					$this->msg = 'Data berhasil disimpan';

					if ($this->return) {
						$this->db->where('user_id', decrypt_url($this->input->post('user'), 'user_id'));
						$this->db->update('users_login', array('level' => 6));
					}
				}
			} elseif ($this->mod == "edit") {
				$data = array(
					'user_id' 	=> decrypt_url($this->input->post('user'), 'user_id'),
					'dept_id' 	=> decrypt_url($this->input->post('instansi'), 'instansi'),
					'updated_at' 	=> date('Y-m-d H:i:s'),
					'updated_by'	=> $this->session->userdata('tpp_user_id')
				);
				$this->return = $this->db->update('pejabat_instansi', $data, ['id' => decrypt_url($this->input->post('id'), 'pejabatinstansi_id')]);
				$this->msg = 'Data berhasil diperbarui';
			}
			if ($this->return) {
				$this->result = array(
					'status' => true,
					'message' => $this->msg
				);
			} else {
				$this->result = array(
					'status' => false,
					'message' => 'Data gagal disimpan atau data telah ada'
				);
			}
		} else {
			$this->result = array(
				'status' => false,
				'message' => validation_errors(),
			);
		}

		if ($this->result) {
			$this->output->set_output(json_encode($this->result));
		} else {
			$this->output->set_output(json_encode(['status' => FALSE, 'message' => 'Gagal mengambil data.']));
		}
	}

	public function AjaxDel()
	{
		$this->output->unset_template();
		$id = decrypt_url($this->input->get('id'), "pejabatinstansi_id");

		// Ambil 'user_id' sebelum menghapus data
		$user_id = $this->db->select('user_id')->get_where('pejabat_instansi', ['id' => $id])->row('user_id');

		$this->del = $this->db->delete('pejabat_instansi', ['id' => $id]);

		if ($this->del) {
			// Jika data berhasil dihapus, ubah kembali level ke 3 di tabel 'users_login'
			$this->db->where('user_id', $user_id);
			$this->db->update('users_login', ['level' => 3]);

			$this->output->set_output(json_encode(['status' => TRUE, 'message' => 'Data berhasil dihapus.']));
		} else {
			$this->output->set_output(json_encode(['status' => FALSE, 'message' => 'Gagal dihapus atau data sedang digunakan.']));
		}
	}


	public function cetak($dept_id)
	{
		$this->output->unset_template();
		$this->load->library('Tpdf');
		$this->data['instansi'] = $this->m_instansi->GetInstansi(decrypt_url($dept_id, 'instansi'))->row();
		$this->data['user']		= $this->m_user->GetUserAllPejabat(decrypt_url($dept_id, 'instansi'), 8)->result();
		$this->load->view('ksb_kepegawaian/v_cetak', $this->data);
	}
}

/* End of file Ksb_keuangan.php */
/* Location: ./application/modules/Administrator/controllers/Ksb_kepegawaian.php */
