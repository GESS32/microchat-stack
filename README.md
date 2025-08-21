# microchat-stack

> **Note:** repository is in the early stages of development.

This repository is a **chat template** built on a microservices architecture.  
It is designed as an extensible foundation for high-load applications.

---

## Roadmap

* Implement basic one-to-one chat between users
* Support message delivery through Kafka
* Scale the WebSocket hub for real-time connections
* Add secure data encryption to ensure message confidentiality
* Set up a CI/CD pipeline for automated deployments
* Extend with additional services (notifications, file sharing, media handling...)

---

## Future Plans
One possible long-term improvement is a migration from Docker Swarm to Kubernetes.  
This is not part of the current roadmap but is considered as a potential next step only after the system is stable.

> While implementing Kafka in Docker Swarm, I realized it’s **better to use Kubernetes** because it handles stateful workloads more reliably, automates updates and scaling, and simplifies access management and observability.
> **Docker Swarm is more suitable for simple or temporary setups** with a single broker, but beyond that it quickly turns into heavy manual operational work.

---

## Docs
* [Deployment](docs/deployment.md)
