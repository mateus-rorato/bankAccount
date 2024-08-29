# Sistema de Gerenciamento de Contas Bancárias

Este é um projeto Laravel que implementa um sistema de gerenciamento de contas bancárias, incluindo funcionalidades para criar contas, realizar transferências imediatas e agendadas entre contas, e consultar o saldo das contas.

## Funcionalidades

<ul>
    <li><strong>Criação de Contas Bancárias</strong>: Permite criar uma nova conta bancária com nome e saldo inicial.</li>
    <li><strong>Transferência Imediata</strong>: Realiza transferências imediatas entre duas contas bancárias, garantindo que a conta de origem não fique com saldo negativo.</li>
    <li><strong>Agendamento de Transferências</strong>: Permite agendar transferências que serão processadas posteriormente pelo agendador.</li>
    <li><strong>Consulta de Contas</strong>: Consulta todas as contas ou uma conta específica pelo seu ID.</li>
</ul>

## Requisitos

<ul>
    <li>PHP 8.0 ou superior</li>
    <li>Composer</li>
    <li>MySQL</li>
    <li>Laravel 9.x</li>
</ul>

## Rotas da API

<ol>
    <li>
        <strong>Criar Conta Bancária</strong><br>
        <ul>
            <li><strong>Endpoint</strong>: <code>/contas</code></li><br>
            <li><strong>Método</strong>: <code>POST</code></li><br>
            <li><strong>Descrição</strong>: Cria uma nova conta bancária.</li><br>
            <li><strong>Parâmetros</strong>:</li>
            <ul>
                <li><code>nome</code>: Nome do titular da conta (<code>string</code>).</li><br>
                <li><code>saldo</code>: Saldo inicial da conta (<code>decimal</code>).</li>
            </ul>
        </ul>
    </li>
    <br>
    <li>
        <strong>Transferir Entre Contas</strong><br>
        <ul>
            <li><strong>Endpoint</strong>: <code>/transfer</code></li><br>
            <li><strong>Método</strong>: <code>POST</code></li><br>
            <li><strong>Descrição</strong>: Transfere um valor de uma conta para outra imediatamente.</li><br>
            <li><strong>Parâmetros</strong>:</li>
            <ul>
                <li><code>sender</code>: ID da conta de origem (<code>int</code>).</li><br>
                <li><code>receiver</code>: ID da conta de destino (<code>int</code>).</li><br>
                <li><code>amount</code>: Valor a ser transferido (<code>decimal</code>).</li>
            </ul>
        </ul>
    </li>
    <br>
    <li>
        <strong>Agendar Transferência</strong><br>
        <ul>
            <li><strong>Endpoint</strong>: <code>/agendar</code></li><br>
            <li><strong>Método</strong>: <code>POST</code></li><br>
            <li><strong>Descrição</strong>: Agenda uma transferência entre contas para ser processada posteriormente.</li><br>
            <li><strong>Parâmetros</strong>:</li>
            <ul>
                <li><code>sender</code>: ID da conta de origem (<code>int</code>).</li><br>
                <li><code>receiver</code>: ID da conta de destino (<code>int</code>).</li><br>
                <li><code>amount</code>: Valor a ser transferido (<code>decimal</code>).</li><br>
                <li><code>schedule_date</code>: Data e hora para agendar a transferência (<code>datetime</code>).</li>
            </ul>
        </ul>
    </li>
    <br>
    <li>
        <strong>Consultar Contas</strong><br>
        <ul>
            <li><strong>Endpoint</strong>: <code>/conta</code></li><br>
            <li><strong>Método</strong>: <code>GET</code></li><br>
            <li><strong>Descrição</strong>: Consulta todas as contas ou uma conta específica.</li><br>
            <li><strong>Parâmetros</strong>:</li>
            <ul>
                <li><code>id</code> (opcional): ID da conta a ser consultada (<code>int</code>).</li>
            </ul>
        </ul>
    </li>
</ol>
