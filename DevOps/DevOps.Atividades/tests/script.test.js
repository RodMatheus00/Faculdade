const test = require('node:test');
const assert = require('node:assert/strict');

const {
  normalizeText,
  createWorkflowMessage,
  calculateCoveragePercentage,
  isValidPullRequestEvent,
  getQualityStatus,
} = require('../script');

test('normalizeText remove espacos extras no inicio, meio e fim do texto', () => {
  const result = normalizeText('  Projeto   DevOps   GitHub Actions  ');

  assert.equal(result, 'Projeto DevOps GitHub Actions');
});

test('createWorkflowMessage retorna mensagem com nome do projeto normalizado', () => {
  const result = createWorkflowMessage('  Faculdade   DevOps  ');

  assert.equal(result, 'Projeto Faculdade DevOps configurado para CI/CD.');
});

test('createWorkflowMessage retorna mensagem padrao quando o nome do projeto esta vazio', () => {
  const result = createWorkflowMessage('   ');

  assert.equal(result, 'Projeto sem nome configurado para CI/CD.');
});

test('calculateCoveragePercentage calcula percentual arredondado de cobertura', () => {
  const result = calculateCoveragePercentage(4, 5);

  assert.equal(result, 80);
});

test('calculateCoveragePercentage retorna zero quando o total de itens e invalido', () => {
  const result = calculateCoveragePercentage(3, 0);

  assert.equal(result, 0);
});

test('isValidPullRequestEvent valida evento de pull request', () => {
  assert.equal(isValidPullRequestEvent('pull_request'), true);
  assert.equal(isValidPullRequestEvent('push'), false);
});

test('getQualityStatus retorna validado somente quando os arquivos principais existem', () => {
  assert.equal(getQualityStatus(true, true, true), 'validado');
  assert.equal(getQualityStatus(true, false, true), 'pendente');
});
