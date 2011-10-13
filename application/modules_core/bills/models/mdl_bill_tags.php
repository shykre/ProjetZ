<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class Mdl_Invoice_Tags extends CI_Model {

	public function get_tags($bill_id) {

		$this->db->select('GROUP_CONCAT(mcb_tags.tag) AS tags');

		$this->db->join('mcb_tags', 'mcb_tags.tag_id = mcb_bill_tags.tag_id');

		$this->db->where('mcb_bill_tags.bill_id', $bill_id);

		return $this->db->get('mcb_bill_tags')->row()->tags;

	}

	public function save_tags($bill_id, $tags = NULL) {

		/* Delete any existing tags for this bill */
		$this->db->where('bill_id', $bill_id);

		$this->db->delete('mcb_bill_tags');

		if ($tags) {

			$tags = explode(',', $tags);

			foreach ($tags as $tag) {

				$this->db->where('tag', trim($tag));

				$query = $this->db->get('mcb_tags');

				if (!$query->num_rows()) {

					/* New tag - insert the tag and get the tag_id */
					$db_array = array(
						'tag'	=>	trim($tag)
					);

					$this->db->insert('mcb_tags', $db_array);

					$tag_ids[] = $this->db->insert_id();

				}

				else {

					/* Existing tag - get the tag_id */
					$tag_ids[] = $query->row()->tag_id;

				}

			}

			foreach ($tag_ids as $tag_id) {

				$db_array = array(
					'bill_id'	=>	$bill_id,
					'tag_id'		=>	$tag_id
				);

				$this->db->insert('mcb_bill_tags', $db_array);

			}

		}

	}

}

?>