function normalizeText(value) {
  return String(value ?? '').trim().replace(/\s+/g, ' ');
}

function createWorkflowMessage(projectName) {
  const normalizedProjectName = normalizeText(projectName);

  if (!normalizedProjectName) {
    return 'Projeto sem nome configurado para CI/CD.';
  }

  return `Projeto ${normalizedProjectName} configurado para CI/CD.`;
}

function calculateCoveragePercentage(testedItems, totalItems) {
  if (totalItems <= 0) {
    return 0;
  }

  return Math.round((testedItems / totalItems) * 100);
}

function isValidPullRequestEvent(eventName) {
  return eventName === 'pull_request';
}

function getQualityStatus(hasHtml, hasCss, hasJavaScript) {
  return hasHtml && hasCss && hasJavaScript ? 'validado' : 'pendente';
}

const DevOpsApp = {
  normalizeText,
  createWorkflowMessage,
  calculateCoveragePercentage,
  isValidPullRequestEvent,
  getQualityStatus,
};

if (typeof module !== 'undefined' && module.exports) {
  module.exports = DevOpsApp;
}

if (typeof window !== 'undefined') {
  window.DevOpsApp = DevOpsApp;
}
