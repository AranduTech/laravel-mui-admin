/* eslint-disable i18next/no-literal-string */
export default {
    abilities: {
        create: 'Criar',
        read: 'Ler',
        update: 'Atualizar',
        delete: 'Deletar',
        restore: 'Recuperar',
    },
    aria: { openDrawer: 'Abrir' },
    auth: {
        login: 'Entrar',
        logout: { confirm: 'Tem certeza que deseja sair?' },
        password: {
            confirm: 'Confirmar Senha',
            forgot: 'Esqueceu sua senha?',
            new: 'Nova Senha',
            reset: 'Redefinir Senha',
            send: 'Enviar link de redefinição de senha',
        },
        register: 'Criar Conta',
        remember: 'Lembrar-me',
    },
    dashboard: {
        export: {
            title: 'Exportar Dados',
            message: 'Deseja exportar todos os dados dessa dashboard?',
        }
    },
    cancel: 'Cancelar',
    common: {
        actions: 'Ações',
        all: 'Todos',
        cancel: 'Cancelar',
        deleted: 'Deletado com sucesso!',
        error: 'Erro ao realizar requisição.',
        new: 'Criar',
        none: 'Nenhuma',
        restored: 'Restaurado com sucesso!',
        saved: 'Dados salvos com sucesso!',
        search: 'Buscar',
        submit: 'Salvar',
        delete: 'Deletar',
        softDelete: 'Enviar para lixeira',
        restore: 'Restaurar',
        deleteForever: 'Deletar permanentemente',
        noResults: 'Não há resultados para exibir.',
    },
    error: 'Erro',
    models: {
        role: {
            plural: 'Funções',
            singular: 'Função',
        },
        user: {
            plural: 'Usuários',
            singular: 'Usuário',
        },
    },
    navigate: {
        back: 'Voltar',
        home: 'Início',
        logout: 'Sair',
        menu: 'Menu',
        profile: 'Perfil',
        settings: 'Configurações',
        submit: 'Enviar',
    },
    no: 'Não',
    profile: {
        email: 'Alterar e-mail',
        name: 'Nome',
        password: 'Alterar senha',
        passwordConfirm: 'Confirmar nova senha',
        submit: 'Salvar',
        success: 'Perfil atualizado com sucesso!',
        title: 'Editar Perfil',
    },
    roles: {
        admin: 'Administrador',
        subscriber: 'Assinante',
        manager: 'Gerente',
    },
    table: {
        actions: {
            delete: {
                title: 'Excluir dado',
                confirm: 'Deseja mesmo excluir esse dado?',
            },
            restore: {
                title: 'Recuperar dado',
                confirm: 'Deseja mesmo recuperar esse dado?',
            },
            title: 'Ações',
            select: 'Selecionar ação',
            selectAll: 'Selecionar todos',
            submit: 'Aplicar',
        },
        columns: {
            name: 'Nome',
            abilities: 'Permissões',
            role: 'Papel',
        },
        rowsPerPage: 'Exibir por Página:',
        trashed: 'Lixeira',
        cantEditTrashed: 'Não é possível editar dados enviados para a lixeira. Restaure o item para editá-lo.',
    },
    user: {
        email: 'E-mail',
        name: 'Nome',
        password: 'Senha',
    },
    verification: {
        check: 'Verifique seu email',
        notice: 'Antes de continuar, procure na caixa de entrada do'
            + ' seu e-mail para um link de verificação.',
        notReceived: 'Se você não recebeu o e-mail, clique no botão abaixo.',
        resendButton: 'Enviar email novamente',
        resent: 'Um novo link de verificação foi enviado para o seu endereço de e-mail.',
    },
    welcome: 'Bem-vindo',
    yes: 'Sim',
};
