<?php

/**
 * Widget showInscrevase exibe o botãoo para o usuário se inscrever.
 */
class showInscrevase extends WP_Widget {
	/**
	 * Construtor
	 */
	public function showInscrevase() { parent::WP_Widget(false, $name = 'Exibir Inscreva-se'); }

	/**
	 * Exibição final do Widget (já no sidebar)
	 *
	 * @param array $argumentos Argumentos passados para o widget
	 * @param array $instancia Instância do widget
	 */
	public function widget($argumentos, $instancia) {
		//if (!is_single()) return;
		$link = "/#form-inscrevase";
		$link_target = $instancia['link_target'];
?>
        
		<div id="inscrevase-popup" class="white-popup mfp-hide">
			<?php $page = get_post( $id = $link_target );
                echo apply_filters( 'the_content', $page->post_content);
            ?>
		</div> <!-- #inscrevase-popup .white-popup .mfp-hide -->       
		
        <div class="container btn-inscreva-se">
            <a class="btn secondary-bkg-color open-popup-link" title="<?php _e('Register Now', 'fudge'); ?>" href="#inscrevase-popup">
				<?php _e('Register Now', 'fudge'); ?>
			</a>
        </div><!-- .container btn-inscreva-se -->
        
<?php 

	}


	/**
	 * Salva os dados do widget no banco de dados
	 *
	 * @param array $nova_instancia Os novos dados do widget (a serem salvos)
	 * @param array $instancia_antiga Os dados antigos do widget
	 * 
	 * @return array $instancia Dados atualizados a serem salvos no banco de dados
	 */
	public function update($nova_instancia, $instancia_antiga) {
		$instancia = $nova_instancia;
		
		return $instancia;
	}

	/**
	 * Formulário para os dados do widget (exibido no painel de controle)
	 *
	 * @param array $instancia Instância do widget
	 */
	public function form($instancia) {
		$widget['link'] = (boolean)$instancia['link'];
		$widget['link_target'] = (boolean)$instancia['link_target'];
		?>
		<p><label><input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="checkbox" value="1" <?php if ($widget['link']) echo 'checked="checked"'; ?> /> <?php _e('Exibir o botão Inscreva-se?'); ?></label></p>


	<p>
        <label for="<?php echo $this->get_field_id('link_target'); ?>"><?php _e('Adicionar link para:'); ?></label>
        <?php 
		wp_dropdown_pages(array(
			'id' => $this->get_field_id('link_target'),
			'name' => $this->get_field_name('link_target'),
			'selected' => $instancia['link_target'],
		));
		?>
    </p>
    
	<?php }

}

add_action('widgets_init', create_function('', 'return register_widget("showInscrevase");'));

?>
