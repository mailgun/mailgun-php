workflow "Main" {
  on = "push"
  resolves = ["PHPStan"]
}

action "PHPStan" {
  uses = "docker://oskarstark/phpstan-ga"
  secrets = ["GITHUB_TOKEN"]
  args = "analyse src/ --level=0"
}
